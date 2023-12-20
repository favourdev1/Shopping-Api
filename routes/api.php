<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;






// Access to Non Logged in Information
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/{product}', [ProductController::class, 'show']);
  
});

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Profile
Route::middleware('auth:api')->group(function () {
    
    Route::prefix('profile')->group(function (){
        Route::get('/{user}', [UsersController::class, 'showProfile']);
        Route::put('/update', [UsersController::class, 'updateProfile']);
    });


    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add/{user}', [CartController::class, 'store']);
        // Route::put('/update/{cart}', [CartController::class, 'update']);
        Route::delete('/delete/{cart}', [CartController::class, 'destroy']);
    });
   

});







// Admin Profile
Route::prefix('admin')->middleware(['auth:api','admin'])->group(function () {
    Route::get('/profile', [UsersController::class, 'GetAllUsers']);
    Route::put('/profile', [AdminController::class, 'updateProfile']);
    Route::get('/profileDetails/{user}', [UsersController::class, 'showProfile']);
    
    
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