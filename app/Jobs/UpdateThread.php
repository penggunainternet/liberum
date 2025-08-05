<?php

namespace App\Jobs;

use App\Models\Thread;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Requests\ThreadStoreRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateThread implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $thread;
    private $attributes;
    private $images;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Thread $thread, string $title = null, string $body = null, string $categoryId = null, array $images = [])
    {
        $this->thread = $thread;
        $this->images = $images;

        $attributes = [];
        if ($title !== null) $attributes['title'] = $title;
        if ($body !== null) $attributes['body'] = Purifier::clean($body);
        if ($categoryId !== null) $attributes['category_id'] = $categoryId;
        if ($title !== null) $attributes['slug'] = $this->generateUniqueSlug($title, $thread->id);

        $this->attributes = $attributes;
    }

    public static function fromRequest(Thread $thread, ThreadStoreRequest $request): self
    {
        $images = $request->hasFile('images') ? $request->file('images') : [];

        return new static(
            $thread,
            $request->title(),
            $request->body(),
            $request->category(),
            $images
        );
    }

    /**
     * Execute the job.
     *
     * @return Thread
     */
    public function handle(): Thread
    {
        $this->thread->update($this->attributes);

        // Handle new image uploads
        if (!empty($this->images)) {
            $this->thread->addMediaFromArray($this->images, 'threads');
        }

        $this->thread->save();

        return $this->thread;
    }

    /**
     * Generate unique slug for thread (excluding current thread on update)
     */
    private function generateUniqueSlug(string $title, int $excludeId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        // Check if slug already exists (exclude current thread for update)
        while (Thread::where('slug', $slug)->when($excludeId, function ($query) use ($excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
