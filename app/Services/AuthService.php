<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    // ✅ Register User
   public function register(array $data)
{
    // since image is required → no need for null check
    $imagePath = $data['image']->store('profiles', 'public');

    $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'],
        'bio'      => $data['bio'],
        'image'    => $imagePath, // ✅ always present
        'password' => Hash::make($data['password']),
        'role' => 'user',
    ]);

    $user->assignRole('User'); 

    return [
        'user' => $user,
        'role' => $user->getRoleNames(),
    ];
}
    // ✅ Login (Passport - NO SESSION)
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('authToken')->accessToken;

        return [
            'user'  => $user,
            'role' => $user->getRoleNames(),
            'token' => $token,
        ];
    }

    // ✅ Logout (Revoke Token)
    public function logout($user)
    {
        $user->token()->revoke();
        return true;
    }

    // ✅ Get Auth User (API)
    public function user($request)
    {
        return[
            'user' => $request->user(),
            'role' => $request->user()->getRoleNames(),
        ] ;// Passport user
    }

    // ✅ Change Password
    public function changePassword($user, array $data)
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return false;
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return true;
    }

    // ✅ Update Profile
     public function updateProfile($user, array $data)
    {
        // 🔥 Handle Image Update
        if (isset($data['image'])) {

            // delete old image
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // store new image
            $data['image'] = $data['image']->store('profiles', 'public');
        }

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $user->phone,
            'bio'   => $data['bio'] ?? $user->bio,
            'image' => $data['image'] ?? $user->image,
        ]);

        return $user;
    }
}