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

        $user = DB::table('users')->where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        session()->put('user_id', $user->id);
        session()->put('user_name', $user->name);
        session()->put('user_username', $user->username);
        session()->put('role_id', $user->role_id);

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
            return back()->with('error', 'OTP expired');
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

        return redirect('/dashboard')->with('success', 'Profile completed!');
    }
}
