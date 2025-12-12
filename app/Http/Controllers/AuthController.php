<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (auth()->check()) {
            return redirect('/admin');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        // Cek apakah email sudah diverifikasi hanya untuk role_id 3
        if ($user->role_id == 3 && is_null($user->email_verified_at)) {
            return redirect()->route('otp.form', ['email' => $user->email]);
        }


        // Cek apakah name atau phone masih kosong/null
        if (empty($user->name) || empty($user->phone)) {
            // Login dulu supaya auth()->user() ada
            Auth::login($user);
            return redirect()->route('profile.complete');
        }

        // Login user
        Auth::login($user);

        return redirect('/admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout');
    }

    public function registerForm() {
        if (auth()->check()) {
            return redirect('/admin');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'role_id' => 3, // role user
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otp = rand(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code' => $otp,
            'type' => 'email',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('otp.form', ['email' => $user->email]);
    }

    // AuthController.php
    public function resendOtp(Request $request)
    {
        // Pastikan ada query email
        $email = $request->query('email');
        if (!$email) {
            return back()->with('error', 'Email tidak ditemukan untuk resend OTP.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->with('error', 'User dengan email ini tidak ditemukan.');
        }

        // Buat OTP baru
        $otp = rand(100000, 999999);

        // Tandai OTP lama sebagai expired / used
        Otp::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Simpan OTP baru
        Otp::create([
            'user_id' => $user->id,
            'code' => $otp,
            'type' => 'email',
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim email OTP baru
        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('otp.form', ['email' => $user->email])
                        ->with('success', 'OTP baru telah dikirim ke email Anda.');
    }

    public function verifyOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'email' => $request->email
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $otp = Otp::where('user_id', $user->id)
                ->where('code', $request->code)
                ->whereNull('used_at')
                ->first();

        if (!$otp) {
            return back()->with('error', 'Invalid OTP');
        }

        if ($otp->expires_at < now()) {
            return back()->with('error', 'OTP expired, resend OTP now!');
        }

        // Set OTP used
        $otp->update(['used_at' => now()]);

        // Verify email
        $user->update([
            'email_verified_at' => now()
        ]);

        // Login user
        auth()->login($user);

        return redirect()->route('profile.complete');
    }

    public function completeForm()
    {
        return view('auth.complete-profile');
    }

    public function completeStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|unique:users,phone,' . auth()->id(),
            'gender' => 'nullable|in:male,female,other',
            'birthdate' => 'nullable|date',
            'address' => 'nullable',
            'city' => 'nullable',
            'province' => 'nullable',
            'country' => 'nullable',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        $data = $request->only([
            'name', 'phone', 'gender', 'birthdate',
            'address', 'city', 'province', 'country'
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        // Logout user dan hapus semua session
        auth()->logout();
        session()->flush();

        // Redirect ke login dengan alert success
        return redirect()->route('login')->with('success', 'Profile berhasil disimpan. Silakan login kembali.');
    }

}
