<?php

namespace App\Http\Controllers\Pages;

use App\Contracts\ViewsContract;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use App\Models\Thread;
use App\Models\Category;
use App\Jobs\CreateThread;
use App\Jobs\UpdateThread;
use Illuminate\Http\Request;
use App\Policies\ThreadPolicy;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\ThreadStoreRequest;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\UpdateThreadRequest;
use App\Jobs\SubscribeToSubscriptionAble;
use App\Jobs\UnsubscribeFromSubscriptionAble;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Database\Eloquent\Builder;

class ThreadController extends Controller
{
    public function __construct()
    {
        return $this->middleware([Authenticate::class, EnsureEmailIsVerified::class])->except(['index', 'show']);
    }

    public function index()
    {
        return view('pages.threads.index', [
            'threads' => Thread::with(['media', 'images'])
                             ->orderBy('id', 'desc')
                             ->paginate(10),
        ]);
    }

    public function create()
    {
        return view('pages.threads.create', [
            'categories'    => Category::all(),
            'tags'          => Tag::all(),
        ]);
    }

    public function store(StoreThreadRequest $request)
    {
        $images = $request->hasFile('images') ? $request->file('images') : [];

        // Process tags - convert comma-separated string to tag IDs
        $tagIds = [];
        if ($request->input('tags')) {
            $tagNames = array_map('trim', explode(',', $request->input('tags')));
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName, 'slug' => \Illuminate\Support\Str::slug($tagName)]);
                    $tagIds[] = $tag->id;
                }
            }
        }

        $thread = new CreateThread(
            $request->input('title'),
            $request->input('body'),
            $request->input('category_id'),
            $tagIds,
            auth()->user(),
            $images
        );

        $this->dispatchSync($thread);

        return redirect()->route('threads.index')->with('success', 'Thread berhasil dibuat!');
    }

    public function show(Category $category, Thread $thread)
    {
        $expireAt = now()->addHours(12);

        views($thread)
            ->cooldown($expireAt)
            ->record();

        // Load images and media relations to avoid N+1 queries
        $thread->load(['images', 'media']);

        return view('pages.threads.show', compact('thread', 'category'));
    }

    public function edit(Thread $thread)
    {
        $this->authorize(ThreadPolicy::UPDATE, $thread);

        $oldTags = $thread->tags()->pluck('id')->toArray();
        $selectedCategory = $thread->category;

        return view('pages.threads.edit', [
            'thread'            => $thread,
            'tags'              => Tag::all(),
            'oldTags'           => $oldTags,
            'categories'        => Category::all(),
            'selectedCategory'  => $selectedCategory,
        ]);
    }

    public function update(UpdateThreadRequest $request, Thread $thread)
    {
        $this->authorize(ThreadPolicy::UPDATE, $thread);

        // Handle removed images
        if ($request->filled('removed_images')) {
            $removedImageIds = array_filter(explode(',', $request->input('removed_images')));
            foreach ($removedImageIds as $imageId) {
                $image = $thread->media()->find($imageId);
                if ($image) {
                    $thread->deleteMedia($image);
                }
            }
        }

        // Handle new images
        $newImages = $request->hasFile('images') ? $request->file('images') : [];

        // Update thread data
        $this->dispatchSync(new UpdateThread(
            $thread,
            $request->input('title'),
            $request->input('body'),
            $request->input('category_id'),
            explode(',', $request->input('tags', '')),
            $newImages
        ));

        return redirect()->route('threads.show', [$thread->category, $thread])->with('success', 'Thread berhasil diperbarui!');
    }

    public function subscribe(Request $request, Thread $thread)
    {
        $this->authorize(ThreadPolicy::SUBSCRIBE, $thread);

        $this->dispatchSync(new SubscribeToSubscriptionAble($request->user(), $thread));

        return redirect()->route('threads.show', [$thread->category->slug(), $thread->slug()])
            ->with('success', 'You have been subscribed to this thread');
    }

    public function unsubscribe(Request $request, Thread $thread)
    {
        $this->authorize(ThreadPolicy::UNSUBSCRIBE, $thread);

        $this->dispatchSync(new UnsubscribeFromSubscriptionAble($request->user(), $thread));

        return redirect()->route('threads.show', [$thread->category->slug(), $thread->slug()])
            ->with('success', 'You have been unsubscribed from this thread');
    }

    public function sortByCategory($slug)
    {
        return view('pages.threads.index', [
            'threads' => Thread::with(['media', 'images'])
                             ->whereHas('category', function (Builder $q) use ($slug) {
                                 $q->where('slug', '=', $slug);
                             })
                             ->paginate(10),
        ]);
    }

    public function thisWeek()
    {
        $threads = Thread::leftJoin('views', 'views.viewable_id', '=', 'threads.id')
            ->selectRaw('threads.*, COUNT(views.viewable_id) AS view_count')
            ->whereBetween('created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()])
            ->groupBy('threads.id')
            ->orderByDesc('view_count');

        return view('pages.threads.index', [
            'threads'       => $threads->paginate(10),
        ]);
    }

    public function allTime()
    {
        $threads = Thread::leftJoin('views', 'views.viewable_id', '=', 'threads.id')
            ->selectRaw('threads.*, COUNT(views.viewable_id) AS view_count')
            ->groupBy('threads.id')
            ->orderByDesc('view_count');

        return view('pages.threads.index', [
            'threads'       => $threads->paginate(10),
        ]);
    }

    public function zeroComment()
    {
        $threads = Thread::leftJoin('replies', 'replies.replyable_id', '=', 'threads.id')
            ->selectRaw('threads.*')
            ->groupBy('threads.id')
            ->whereNull('replies.replyable_id');

        return view('pages.threads.index', [
            'threads'       => $threads->paginate(10),
        ]);
    }

    public function search(Request $request)
    {
        $threads = Thread::where('title', 'LIKE', '%'.$request->search.'%')
            ->orWhere('slug', 'LIKE', '%'.$request->search.'%')
            ->orWhere('body', 'LIKE', '%'.$request->search.'%');

        return view('pages.threads.index', [
            'threads'       => $threads->paginate(10),
        ]);
    }
}
