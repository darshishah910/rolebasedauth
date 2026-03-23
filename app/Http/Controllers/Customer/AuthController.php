<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Helpers\Utils;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    // ✅ Show Register Page
    public function showRegister()
    {
        return Inertia::render('Register');
    }

    private function uploadImage($request)
{
    if ($request->hasFile('image')) {
        return $request->file('image')->store('users', 'public');
    }

    return null;
}
    

    // ✅ Register
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ,
            'bio'      => $data['bio'] ,
            'image' => $this->uploadImage($request),
            'password' => Hash::make($data['password']),
            'role' => 'User',
        ]);

        $user->assignRole('User');

        return redirect()->route('login')->with("success",'register Successfully');
    }

    // ✅ Show Login Page
    public function showLogin()
    {
        return Inertia::render('Login');
    }

    // ✅ Login
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'Invalid credentials'
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}