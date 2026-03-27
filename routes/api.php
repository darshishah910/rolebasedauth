<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProfileController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
  // 🔐 Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    
    Route::get('/user', [UserController::class, 'getUserWithPermissions']);

   
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/stats', [UserController::class, 'stats']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/user/toggle/{id}', [UserController::class, 'toggleStatus']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

   
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/products/toggle/{id}', [ProductController::class, 'toggleStock']);

    
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);

});



Route::middleware('auth:api')->group(function () {
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/users-by-role/{role}', [RoleController::class, 'usersByRole']);
    Route::post('/assign-user-permissions', [RoleController::class, 'assignUserPermissions']);
    Route::get('/all-users-with-permissions', [RoleController::class, 'allUsersWithPermissions']);
});