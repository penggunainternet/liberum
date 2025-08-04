<?php

namespace App\Http\Livewire\Reply;

use App\Models\Reply;
use Livewire\Component;
use App\Policies\ReplyPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Delete extends Component
{
    use AuthorizesRequests;
    public $replyId;
    public $page;

    public function mount($page)
    {
        $this->page = $page;
    }


    public function deleteReply()
    {
        $reply = Reply::findOrFail($this->replyId);
        $this->authorize(ReplyPolicy::DELETE, $reply);

        // Delete reply (will also delete media via model boot hook)
        $reply->delete();

        // Only emit refresh to update the UI, no redirect needed
        $this->emit('refreshReplies');

        session()->flash('success', 'Reply berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.reply.delete');
    }
}
