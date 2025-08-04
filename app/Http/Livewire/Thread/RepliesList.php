<?php

namespace App\Http\Livewire\Thread;

use App\Models\Thread;
use Livewire\Component;

class RepliesList extends Component
{
    public $threadId;
    public $thread;

    protected $listeners = ['refreshReplies' => '$refresh'];

    public function mount(Thread $thread)
    {
        $this->threadId = $thread->id();
        $this->thread = $thread;
    }

    public function render()
    {
        // Always fetch fresh replies
        $replies = $this->thread->repliesRelation()
            ->with(['images', 'media', 'authorRelation'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.thread.replies-list', compact('replies'));
    }
}
