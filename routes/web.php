<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthSession;

Route::get('/', function () {
    return view('welcome');
});

// Halaman register (guest)
Route::get('/register', function () {
    return view('register');
});

// Halaman login (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Halaman admin (harus login)
Route::middleware([AuthSession::class])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
