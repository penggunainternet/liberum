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
    private $tags;
    private $author;
    private $images;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $title, string $body, string $category, array $tags, User $author, array $images = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->category = $category;
        $this->tags = $tags;
        $this->author = $author;
        $this->images = $images;
    }


    public static function fromRequest(ThreadStoreRequest $request): self
    {
        return new static(
            $request->title(),
            $request->body(),
            $request->category(),
            $request->tags(),
            $request->author(),
        );
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): Thread
    {
        $thread = new Thread([
            'title'         => $this->title,
            'slug'          => Str::slug($this->title),
            'body'          => Purifier::clean($this->body),
            'category_id'   => $this->category,
        ]);

        $thread->authoredBy($this->author);
        $thread->syncTags($this->tags);
        $thread->save();

        // Handle image uploads
        if (!empty($this->images)) {
            $thread->addMediaFromArray($this->images, 'threads');
        }

        event(new ThreadWasCreated($thread));

        return $thread;
    }
}
