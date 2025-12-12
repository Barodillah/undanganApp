<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika profile belum lengkap, redirect ke profile.complete
        if (empty($user->name) || empty($user->phone)) {
            return redirect()->route('profile.complete')->with('error', 'Lengkapi profile Anda terlebih dahulu.');
        }

        // Profile lengkap, tampilkan dashboard
        return view('admin.dashboard');
    }
}
