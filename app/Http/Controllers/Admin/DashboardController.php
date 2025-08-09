<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __contruct()
    {
        return $this->middleware([IsAdmin::class, Authenticate::class]);
    }

    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function getActiveUser()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function promoteUser(User $user)
    {
        $user->update(['type' => 3]); // Admin type

        return redirect()->route('admin.users.active')->with('success', 'User berhasil dijadikan admin.');
    }

    public function demoteUser(User $user)
    {
        $user->update(['type' => 1]); // Default type

        return redirect()->route('admin.users.active')->with('success', 'Status admin berhasil dihapus.');
    }

    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.active')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.active')->with('success', 'User berhasil dihapus.');
    }
}
