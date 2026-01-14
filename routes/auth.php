<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('forgot-password', [PasswordController::class, 'request'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordController::class, 'email'])
        ->name('password.email');

    Route::get('reset-password/{token}', [PasswordController::class, 'reset'])
        ->name('password.reset');

    Route::post('reset-password', [PasswordController::class, 'update'])
        ->name('password.update');
});
