<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Marketer\MarketingController;
use App\Http\Controllers\Merchant\ProductController;
use App\Http\Controllers\RedirectorController;
use Illuminate\Support\Facades\Route;

Route::name('user.')->prefix('user')->group(function () {
    Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('verify/otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');
    Route::post('send/otp', [AuthController::class, 'sendOtp'])->name('sendOtp');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refreshToken');
    Route::post('password/reset/token', [AuthController::class, 'getTokenPasswordReset'])->name('getTokenPasswordReset');
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('resetPassword');

    Route::group(['middleware' => ['jwt.verify:marketer|merchant']], function () {

        Route::get('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    });

});

Route::name('merchant.')->prefix('merchant')->middleware(['jwt.verify:merchant'])->group(function () {
    Route::apiResource('products', ProductController::class)->except('update');
    Route::post('products/{product}', [ProductController::class, 'update'])->name('products.update');
});

Route::name('marketer.')->prefix('marketer')->middleware(['jwt.verify:marketer'])->group(function () {
    Route::get('products', [MarketingController::class, 'index'])->name('products.index');
    Route::get('products/visitCount', [MarketingController::class, 'productVisitCounts'])->name('products.visitCount');
    Route::post('products/addForMarketing', [MarketingController::class, 'productAddForMarketing'])->name('products.addForMarketing');
});

Route::get('redirector', [RedirectorController::class, 'redirect'])->name('redirector');
