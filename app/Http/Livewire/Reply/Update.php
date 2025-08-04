<?php

namespace App\Http\Livewire\Reply;

use App\Models\Reply;
use App\Models\User;
use App\Policies\ReplyPolicy;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Update extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $replyId;
    public $replyOrigBody;
    public $replyNewBody;
    public $author;
    public $createdAt;
    public $reply;
    public $images = [];
    public $removeImages = [];

    protected $listeners = ['deleteReply'];

    protected $rules = [
        'replyNewBody' => 'required|string|min:1',
        'images.*' => 'nullable|image|max:2048', // 2MB per image
    ];

    public function mount(Reply $reply)
    {
        $this->replyId = $reply->id();
        $this->replyOrigBody = $reply->body();
        $this->author = $reply->author(); // Allow null author
        $this->createdAt = $reply->created_at;
        $this->reply = $reply->load(['images', 'media']);
        $this->initialize($reply);
    }

    public function removeImage($imageId)
    {
        $this->removeImages[] = $imageId;
    }

    public function updateReply()
    {
        $this->validate();

        $reply = Reply::findOrFail($this->replyId);

        $this->authorize(ReplyPolicy::UPDATE, $reply);

        $reply->body = $this->replyNewBody;
        $reply->save();

        // Handle image removal
        if (!empty($this->removeImages)) {
            foreach ($this->removeImages as $imageId) {
                $media = $reply->media()->find($imageId);
                if ($media) {
                    $media->delete();
                }
            }
            $this->removeImages = [];
        }

        // Handle new image uploads
        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                // image dari livewire upload sudah berupa UploadedFile
                $reply->addMedia($image);
            }
            $this->images = [];
        }

        $this->reply = $reply->load(['images', 'media']);
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
