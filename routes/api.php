like this ??

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Profile
Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [UsersController::class, 'showProfile']);
    Route::put('/profile', [UsersController::class, 'updateProfile']);


       // Admin Profile
       
       Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/profile', [AdminController::class, 'showProfile']);
        Route::put('/profile', [AdminController::class, 'updateProfile']);
    });

   
});

Route::middleware(['auth'])->post('/users/{user}/makeadmin', [AdminController::class, 'setAsAdmin']);