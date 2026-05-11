<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman form login.
     * Jika user sudah login, redirect ke dashboard sesuai role.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi dari form login.
     * Menggunakan LoginRequest untuk validasi input.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Throttle: batasi percobaan login untuk keamanan
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ]);
        }

        // Regenerate session untuk mencegah session fixation attack
        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    /**
     * Logout user dan invalidate session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Helper: redirect berdasarkan role user yang sedang login.
     */
    private function redirectByRole(): RedirectResponse
    {
        return match (Auth::user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'kasir'  => redirect()->route('kasir.dashboard'),
            default  => redirect()->route('login'),
        };
    }
}
