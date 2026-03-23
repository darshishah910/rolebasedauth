<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
     Route::get('/stats', [UserController::class, 'stats']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::post('/user/toggle/{id}', [UserController::class, 'toggleStatus']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/profile', [UserController::class, 'profile']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']); // use PUT if needed
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/products/toggle/{id}', [ProductController::class, 'toggleStock']);
    // Route::apiResource('products',ProductController::class);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

     Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);

   


});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return [
        'user' => $request->user()->load('roles')
    ];
});

Route::middleware(['auth:api', 'role:Admin'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

    Route::post('/users/{id}/role', [UserController::class, 'assignRole']);
});