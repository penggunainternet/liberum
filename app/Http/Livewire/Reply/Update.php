<?php

namespace App\Http\Livewire\Reply;

use App\Models\Reply;
use App\Models\User;
use App\Policies\ReplyPolicy;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Update extends Component
{
    use AuthorizesRequests;

    public $replyId;
    public $replyOrigBody;
    public $replyNewBody;
    public $author;
    public $createdAt;
    public $reply;

    protected $listeners = ['deleteReply'];

    public function mount(Reply $reply)
    {
        $this->replyId = $reply->id();
        $this->replyOrigBody = $reply->body();
        $this->author = $reply->author(); // Allow null author
        $this->createdAt = $reply->created_at;
        $this->reply = $reply->load(['images', 'media']);
        $this->initialize($reply);
    }

    public function updateReply()
    {
        $reply = Reply::findOrFail($this->replyId);

        $this->authorize(ReplyPolicy::UPDATE, $reply);

        $reply->body = $this->replyNewBody;
        $reply->save();
        $this->reply = $reply->load('images');
        $this->initialize($reply);
    }

    public function initialize(Reply $reply)
    {
        $this->replyOrigBody = $reply->body();
        $this->replyNewBody = $this->replyOrigBody;
    }

    public function deleteReply($page)
    {
        // Just flash message, no redirect needed in Livewire
        session()->flash('success', 'Reply Deleted!');
    }

    public function render()
    {
        // Refresh reply dengan images dan media untuk memastikan data terbaru
        $this->reply = Reply::with(['images', 'media'])->find($this->replyId);

        return view('livewire.reply.update');
    }
}
