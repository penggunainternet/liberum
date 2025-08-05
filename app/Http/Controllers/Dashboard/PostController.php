<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $posts = $user->threadsRelation()
                     ->with(['category', 'likesRelation', 'authorRelation'])
                     ->latest()
                     ->paginate(10);

        return view('dashboard.posts.index', compact('posts', 'user'));
    }
}
