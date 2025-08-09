<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Thread;
use Illuminate\Support\Str;
use App\Events\ThreadWasCreated;
use Mews\Purifier\Facades\Purifier;
use App\Http\Requests\ThreadStoreRequest;

class CreateThread
{
    private $title;
    private $body;
    private $category;
    private $author;
    private $images;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $title, string $body, string $category, User $author, array $images = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->category = $category;
        $this->author = $author;
        $this->images = $images;
    }


    public static function fromRequest(ThreadStoreRequest $request): self
    {
        $images = $request->hasFile('images') ? $request->file('images') : [];

        return new static(
            $request->title(),
            $request->body(),
            $request->category(),
            $request->author(),
            $images
        );
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): Thread
    {
        // Determine thread status based on user role
        $status = $this->author->isAdmin() ? 'approved' : 'pending';
        $approvedAt = $this->author->isAdmin() ? now() : null;
        $approvedBy = $this->author->isAdmin() ? $this->author->id : null;

        $thread = new Thread([
            'title'         => $this->title,
            'slug'          => $this->generateUniqueSlug($this->title),
            'body'          => Purifier::clean($this->body),
            'category_id'   => $this->category,
            'status'        => $status,
            'approved_at'   => $approvedAt,
            'approved_by'   => $approvedBy,
        ]);

        $thread->authoredBy($this->author);
        $thread->save();

        // Handle image uploads
        if (!empty($this->images)) {
            $thread->addMediaFromArray($this->images, 'threads');
        }

        event(new ThreadWasCreated($thread));

        return $thread;
    }

    /**
     * Generate unique slug for thread
     */
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        // Check if slug already exists
        while (Thread::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
