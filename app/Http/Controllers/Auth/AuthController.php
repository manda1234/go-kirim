<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MitraProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Login ──────────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo());
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // ── Register ───────────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:customer,mitra',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // ✅ Buat MitraProfile dengan semua kolom yang benar
        if ($user->role === 'mitra') {
            MitraProfile::create([
                'user_id'         => $user->id,
                'vehicle_type'    => 'motor',   // default motor
                'is_online'       => false,
                'rating'          => 5.00,
                'total_trips'     => 0,
                'total_earnings'  => 0,
                'status'          => 'pending', // perlu verifikasi admin
            ]);
        }

        Auth::login($user);
        return redirect($this->redirectTo())->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    // ── Logout ─────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ── Redirect sesuai role ───────────────────────────────────
    private function redirectTo(): string
    {
        return match (Auth::user()->role) {
            'admin'  => route('admin.dashboard'),
            'mitra'  => route('mitra.dashboard'),
            default  => route('customer.dashboard'),
        };
    }
}