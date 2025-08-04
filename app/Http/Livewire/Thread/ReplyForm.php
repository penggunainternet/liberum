<?php

namespace App\Http\Livewire\Thread;

use App\Models\Thread;
use App\Jobs\CreateReply;
use App\Models\Reply;
use App\Policies\ReplyPolicy;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReplyForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $threadId;
    public $thread;
    public $body = '';
    public $images = [];

    protected $rules = [
        'body' => 'required|string|min:1',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
    ];

    protected $messages = [
        'body.required' => 'Isi komentar harus diisi.',
        'images.*.image' => 'File harus berupa gambar.',
        'images.*.mimes' => 'Gambar harus berformat: jpeg, png, jpg, gif, atau webp.',
        'images.*.max' => 'Setiap gambar tidak boleh lebih dari 5MB.',
    ];

    public function mount(Thread $thread)
    {
        $this->threadId = $thread->id();
        $this->thread = $thread;
    }

    public function submitReply()
    {
        $this->authorize(ReplyPolicy::CREATE, Reply::class);

        $this->validate();

        // Convert uploaded files to array for the job
        $imageFiles = [];
        foreach ($this->images as $image) {
            $imageFiles[] = $image;
        }

        $createReplyJob = new CreateReply(
            $this->body,
            auth()->user(),
            $this->thread,
            $imageFiles
        );

        // Execute the job directly
        $reply = $createReplyJob->handle();

        // Reset form
        $this->reset(['body', 'images']);

        // Emit event to refresh replies list
        $this->emit('refreshReplies');

        session()->flash('success', 'Reply berhasil ditambahkan!');
    }    public function render()
    {
        return view('livewire.thread.reply-form');
    }
}
