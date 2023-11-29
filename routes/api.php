<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

// Access to Non Logged in Information
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Profile
Route::middleware('auth:api')->group(function () {
    Route::get('/profile/{user}', [UsersController::class, 'showProfile']);
    Route::put('/profile/update', [UsersController::class, 'updateProfile']);
});

// Admin Profile
Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/profile', [UsersController::class, 'GetAllUsers']);
    Route::put('/profile', [AdminController::class, 'updateProfile']);


    // admin-users functionalities
    Route::prefix('users')->group(function () {
        Route::post('/{user}/makeadmin', [AdminController::class, 'setAsAdmin']);
        Route::post('/{user}/removeadmin', [AdminController::class, 'disableAdmin']);
    });

    

    // Admin -> product Functionalities
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });
});