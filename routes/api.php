<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Auth::routes();

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [RegisterController::class, 'login'])->name('login');
// Route::post('forgot_password', 'Auth\RegisterController@forgot_password')->name('forgot_password');
// Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

// protected route
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [RegisterController::class, 'logout'])->name('logout');
    Route::any('/get_user', [UserController::class, 'show']);
    Route::any('/update_profile', [UserController::class, 'update']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
