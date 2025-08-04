<?php

namespace App\Http\Controllers\Pages;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Jika user sudah login, redirect ke threads
        if (Auth::check()) {
            return redirect()->route('threads.index');
        }

        // Jika belum login, tampilkan halaman guest
        return view('home.index');
    }
}
