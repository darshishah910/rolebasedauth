<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileService
{
    /**
     * ✅ Get Profile
     */
  public function getProfile($user)
{
    if (!$user) {
        return null;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'bio' => $user->bio,
        'role' => $user->getRoleNames()->first(),
        'image' => $user->image
            ? asset('storage/' . $user->image)
            : null,
    ];
}

    /**
     * ✅ Update Profile
     */
    public function updateProfile($user, array $data)
    {
        // ✅ Image upload
        if (isset($data['image'])) {

            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $data['image'] = $data['image']->store('profiles', 'public');
        }

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'bio'   => $data['bio'] ?? $user->bio,
            'image' => $data['image'] ?? $user->image,
        ]);

        return $user;
    }

    /**
     * ✅ Change Password
     */
    public function changePassword($user, array $data)
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return [
                'status' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return [
            'status' => true,
            'message' => 'Password changed successfully'
        ];
    }
}