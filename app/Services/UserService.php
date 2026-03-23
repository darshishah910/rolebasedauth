<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    // ✅ Get all users
    public function getAllUsers()
    {
        return User::with('roles')->where('role', '!=', 'admin')->latest()->get()->map(function ($user) {
            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'bio'       => $user->bio,
                'is_active' => $user->is_active,
                'role'      => $user->getRoleNames()->first() ,
                'image'     => $user->image
                    ? asset('storage/' . $user->image)
                    : null,
            ];
        });
    }

    // ✅ Create user
    public function createUser(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('profiles', 'public');
        }

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'],
            'bio'       => $data['bio'] ?? null,
            'image'     => $data['image'] ?? null,
            'password'  => Hash::make($data['password']),
            // 'role'      => strtolower($data['role']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        $user->assignRole($data['role']);

        return $user;
    }

    // ✅ Update user
    public function updateUser($id, array $data)
    {
        $user = User::findOrFail($id);

        if (isset($data['image'])) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $data['image'] = $data['image']->store('profiles', 'public');
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'],
            'bio'       => $data['bio'] ?? $user->bio,
            'image'     => $data['image'] ?? $user->image,
            // 'role'      => strtolower($data['role']),
            'is_active' => $data['is_active'] ?? $user->is_active,
        ]);

        $user->syncRoles([$data['role']]);

        return $user;
    }

    // ✅ Toggle active/inactive
    public function toggleStatus($id, $value)
    {
        $user = User::findOrFail($id);
        
        $isActive = $value == 1 ? (bool)$value : !$user->is_active;
        // dd($isActive);

        $user->update([
            'is_active' => (bool)$isActive
        ]);

    // dd($user);
        return $user;
    }

    // ✅ Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return true;
    }

    public function getStats()
{
    return [
        'total' => User::where('role', '!=', 'admin')->count(),
        'active' => User::where('role', '!=', 'admin')
                        ->where('is_active', true)->count(),
        'inactive' => User::where('role', '!=', 'admin')
                        ->where('is_active', false)->count(),
    ];
}
}