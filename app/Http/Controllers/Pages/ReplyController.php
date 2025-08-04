<?php

namespace App\Http\Controllers\Pages;

use App\Models\Reply;
use App\Models\Thread;
use App\Jobs\CreateReply;
use Illuminate\Http\Request;
use App\Policies\ReplyPolicy;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\CreateReplyRequest;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware([Authenticate::class, EnsureEmailIsVerified::class]);
    }

    public function store(CreateReplyRequest $request)
    {
        $this->authorize(ReplyPolicy::CREATE, Reply::class);

        $reply = $this->dispatchSync(CreateReply::fromRequest($request));

        // Clear any cached relations for the thread
        $thread = $reply->replyAble();
        $thread->unsetRelation('repliesRelation');

        return back()->with('success', 'Reply Created');
    }

    public function redirect($id, $type)
    {
        $reply = Reply::where('replyable_id', $id)->where('replyable_type', $type)->firstOrFail();

        if ($type === 'threads') {
            $thread = Thread::find($id);
            return redirect()->route('threads.show', [$thread->category->slug(), $thread->slug()]);
        }

        return redirect()->route('threads.index');
    }
}
