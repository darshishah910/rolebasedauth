<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    // ✅ Register User
    public function register(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('User');

        return [
            'user'  => $user,
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
        return $request->user(); // Passport user
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
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'bio'   => $data['bio'] ?? null,
        ]);

        return $user;
    }
}