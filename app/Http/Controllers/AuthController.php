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
use Carbon\Carbon;

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

        /**
         * ===============================
         * WAJIB VERIFIKASI EMAIL
         * ===============================
         */
        if ($user->role_id != 1 && is_null($user->email_verified_at)) {

            $otp = Otp::where('user_id', $user->id)
                ->where('type', 'email')
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (!$otp) {
                $otp = Otp::create([
                    'user_id'    => $user->id,
                    'code'       => rand(100000, 999999),
                    'type'       => 'email',
                    'expires_at' => now()->addMinutes(5),
                ]);

                Mail::to($user->email)->send(
                    new OtpMail($otp->code, 'email')
                );
            }

            return redirect()->route('otp.form', [
                'email' => $user->email,
                'type'  => 'email'
            ]);
        }

        /**
         * ===============================
         * LOGIN USER
         * ===============================
         */
        Auth::login($user);

        // ✅ UPDATE LAST LOGIN (JAKARTA TIME)
        $user->update([
            'last_login_at' => now('Asia/Jakarta'),
        ]);

        // Cek profile belum lengkap
        if (empty($user->name) || empty($user->phone)) {
            return redirect()->route('profile.complete');
        }

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

        Mail::to($user->email)->send(new OtpMail($otp, 'email'));

        return redirect()->route('otp.form', ['email' => $user->email,'type'  => 'email']);
    }

    // AuthController.php
    public function resendOtp(Request $request)
    {
        // Pastikan ada query email
        $email = $request->query('email');
        $type = $request->query('type');
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
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim email OTP baru
        Mail::to($user->email)->send(new OtpMail($otp, $type));

        return redirect()->route('otp.form', ['email' => $user->email, 'type' => $type])
                        ->with('success', 'OTP baru telah dikirim ke email Anda.');
    }

    public function verifyOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'email' => $request->email,
            'type' => $request->type
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'type' => 'required'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Ambil OTP berdasarkan user, code, dan type
        $otp = Otp::where('user_id', $user->id)
                ->where('code', $request->code)
                ->where('type', $request->type) // tambahkan ini
                ->whereNull('used_at')
                ->first();

        if (!$otp) {
            return back()->with('error', 'OTP tidak valid untuk proses ini.');
        }

        if ($otp->expires_at < now()) {
            return back()->with('error', 'OTP expired, silakan kirim ulang.');
        }

        // Tandai OTP sudah digunakan
        $otp->update(['used_at' => now()]);

        // Jika OTP untuk registrasi
        if ($otp->type === 'email') {

            // Verifikasi email
            $user->update([
                'email_verified_at' => now()
            ]);

            // Login user
            auth()->login($user);

            return redirect()->route('profile.complete')
                            ->with('success', 'Email berhasil diverifikasi!');
        }

        // Jika OTP untuk reset password
        if ($otp->type === 'forgot') {

            // Simpan data dalam session agar bisa ganti password
            session(['reset_email' => $user->email]);

            return redirect()->route('forgot.reset')
                            ->with('success', 'Silakan buat password baru.');
        }

        return back()->with('error', 'Tipe OTP tidak dikenali.');
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

    public function forgotForm()
    {
        return view('auth.forgot');
    }

    public function sendOtpForgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar.');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Simpan OTP baru
        Otp::create([
            'user_id'    => $user->id,
            'code'       => $otp,
            'type'       => 'forgot',          // tipe khusus agar OTP tidak campur dengan registrasi
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim email OTP
        Mail::to($user->email)->send(new OtpMail($otp, 'forgot'));

        // Redirect ke form OTP sama seperti registrasi
        return redirect()->route('otp.form', ['email' => $user->email, 'type'  => 'forgot'])
                        ->with('success', 'Kode OTP berhasil dikirim ke email Anda.');
    }

    public function resetPasswordForm()
    {
        return view('auth.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus session reset
        session()->forget('reset_email');

        return redirect()->route('login')
                        ->with('success', 'Password berhasil direset. Silakan login.');
    }

}
