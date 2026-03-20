<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        // session login (NOT passport)
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'Invalid credentials'
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return redirect('/login');
    }
}