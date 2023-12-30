<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', 'Api\AuthenticationController@register')->name('register');
Route::post('login', 'Api\AuthenticationController@login')->name('login');
Route::post('forgot_password', 'Api\AuthenticationController@forgot_password')->name('forgot_password');
Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

// protected route
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', 'Api\AuthenticationController@logout');
    Route::any('/get_user', [UserController::class, 'get_user']);
    Route::any('/change_password', [UserController::class, 'change_password']);
    Route::any('/update_profile', [UserController::class, 'update_profile']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
