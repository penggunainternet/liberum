<?php

namespace App\Http\Controllers\Admin;

use App\Models\Thread;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThreadModerationController extends Controller
{
    public function pending()
    {
        $threads = Thread::pending()
            ->with(['authorRelation', 'category'])
            ->whereHas('authorRelation', function($query) {
                // Hanya tampilkan thread dari user non-admin
                $query->where('type', '!=', 3); // type 3 = admin
            })
            ->latest()
            ->paginate(20);

        return view('admin.threads.pending', compact('threads'));
    }

    public function approved()
    {
        $threads = Thread::approved()
            ->with(['authorRelation', 'category', 'approvedBy'])
            ->whereHas('authorRelation', function($query) {
                // Hanya tampilkan thread dari user non-admin yang di-approve
                $query->where('type', '!=', 3); // type 3 = admin
            })
            ->latest('approved_at')
            ->paginate(20);

        return view('admin.threads.approved', compact('threads'));
    }

    public function rejected()
    {
        $threads = Thread::rejected()
            ->with(['authorRelation', 'category', 'approvedBy'])
            ->whereHas('authorRelation', function($query) {
                // Hanya tampilkan thread dari user non-admin
                $query->where('type', '!=', 3); // type 3 = admin
            })
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.threads.rejected', compact('threads'));
    }

    public function approve(Thread $thread)
    {
        $thread->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Thread berhasil disetujui.');
    }

    public function reject(Thread $thread)
    {
        $thread->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Thread berhasil ditolak.');
    }

    public function show(Thread $thread)
    {
        $thread->load(['authorRelation', 'category', 'approvedBy']);

        return view('admin.threads.show', compact('thread'));
    }
}
