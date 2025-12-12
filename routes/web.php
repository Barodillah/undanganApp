<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthSession;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/otp/verify', [AuthController::class, 'verifyOtpForm'])->name('otp.form');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/resendotp', [AuthController::class, 'resendOtp'])->name('otp.resend');

// Halaman login (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [AuthController::class, 'completeForm'])->name('profile.complete');
    Route::post('/profile', [AuthController::class, 'completeStore'])->name('profile.complete.store');
});
