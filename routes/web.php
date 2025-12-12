<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthSession;

Route::get('/', function () {
    return view('welcome');
});

// Halaman register (guest)
// Route::get('/register', function () {
//     return view('register');
// });

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/otp/verify', [AuthController::class, 'verifyOtpForm'])->name('otp.form');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/profile', [AuthController::class, 'completeForm'])->middleware('auth')->name('profile.complete');
Route::post('/profile', [AuthController::class, 'completeStore'])->middleware('auth')->name('profile.complete.store');

// Halaman login (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Halaman admin (harus login)
Route::middleware([AuthSession::class])->group(function () {
    Route::get('/admin', function () {
        $username = session('user_username'); // ambil dari session
        return view('admin.dashboard', compact('username'));
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
