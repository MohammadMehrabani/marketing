<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->prefix('user')->group(function () {
    Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('verify/otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');
    Route::post('send/otp', [AuthController::class, 'sendOtp'])->name('sendOtp');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refreshToken');
    Route::post('password/reset', [AuthController::class, 'changePassword'])->name('changePassword');
    Route::post('password/reset/token', [AuthController::class, 'getTokenPasswordReset'])->name('getTokenPasswordReset');

    Route::group(['middleware' => ['jwt.verify:marketer|merchant']], function () {

        Route::get('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    });

});
