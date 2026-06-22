<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // --- Rate limiting: 5 attempts per minute per email+ip ---
        $key = 'login:' . strtolower($request->email) . ':' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ]);
        }

        // --- Find user by email ---
        $user = User::where('email', $request->email)->first();

        // --- Account lockout check ---
        if ($user && $user->locked_until && now()->lt($user->locked_until)) {
            $waitMinutes = (int) ceil(now()->diffInMinutes($user->locked_until, false) * -1);
            throw ValidationException::withMessages([
                'email' => "Akun terkunci. Silakan coba lagi dalam {$waitMinutes} menit.",
            ]);
        }

        // --- Attempt login ---
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($key);

            // Reset lockout on success
            if ($user) {
                $user->update(['login_attempts' => 0, 'locked_until' => null]);
            }

            $request->session()->regenerate();

            return redirect()->route(
                Auth::user()->role === 'admin' ? 'admin.dashboard' : 'kasir.dashboard'
            );
        }

        // --- Login failed: increment attempts ---
        RateLimiter::hit($key, 60); // decays in 60s

        if ($user) {
            $attempts = $user->login_attempts + 1;
            $data = ['login_attempts' => $attempts];

            // Lock account after 5 consecutive failures (on top of rate limiter)
            if ($attempts >= 5) {
                $data['locked_until'] = now()->addMinutes(15);
            }

            $user->update($data);

            if ($attempts >= 5) {
                throw ValidationException::withMessages([
                    'email' => 'Akun terkunci setelah 5 percobaan gagal. Silakan coba lagi dalam 15 menit.',
                ]);
            }
        }

        $remaining = 5 - ($user->login_attempts ?? 0);
        throw ValidationException::withMessages([
            'email' => "Email atau password salah. {$remaining} percobaan tersisa.",
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ── Forgot Password ──────────────────────────
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Reset lockout
            $user->update(['login_attempts' => 0, 'locked_until' => null]);

            // In production: send email with reset link.
            // For now, display password directly to admin.
            return back()->with('status', 'Akun ditemukan. Silakan hubungi administrator untuk reset password: ' . ($user->role === 'admin' ? 'admin@stitchpos.com' : 'kasir@stitchpos.com'));
        }

        return back()->with('status', 'Jika email terdaftar, instruksi reset password akan dikirimkan.');
    }

    // ── Contact Admin ────────────────────────────
    public function showContact()
    {
        return view('auth.contact');
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|max:2000',
        ]);

        // In production: dispatch contact email notification.
        // For now, just flash success.
        return back()->with('status', 'Pesan Anda telah terkirim. Admin akan menghubungi Anda melalui email dalam 1×24 jam.');
    }
}
