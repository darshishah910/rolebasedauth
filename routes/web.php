<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\TaskController;
use App\Http\Controllers\Customer\RoleController;
use App\Http\Controllers\Customer\ProfileController;
use Inertia\Inertia;

// Home → redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Dashboard
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// User actions
Route::delete('/user/{id}', [UserController::class, 'delete']);

Route::get('/products', [ProductController::class, 'index'])->name('products');

 Route::get('/tasks', [TaskController::class, 'index']);

Route::get('/roles',[RoleController::class,'index']);

Route::get('/roles-list', function () {
    return Inertia::render('RolesList'); 
});

Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/profile/update', [ProfileController::class, 'update']);
Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);