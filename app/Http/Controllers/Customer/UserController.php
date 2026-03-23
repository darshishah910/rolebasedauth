<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ✅ Dashboard page
    public function dashboard()
    {
        $users = User::with('roles')->latest()->get();

        return Inertia::render('dashboard', [
            'users' => $users
        ]);
    }

    // ✅ Toggle Active
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back()->with('success', 'Status updated');
    }

    // ✅ Delete user
    public function delete($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'User deleted');
    }
}