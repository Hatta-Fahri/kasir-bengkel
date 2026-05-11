<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan user yang sudah login memiliki role yang tepat
     * sebelum mengakses route tertentu.
     *
     * Penggunaan di routes/web.php:
     *   ->middleware('role:admin')
     *   ->middleware('role:kasir')
     *
     * @param  Closure(Request): Response  $next
     * @param  string  ...$roles  Role yang diizinkan (bisa lebih dari satu)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Guard: user harus sudah login (harusnya sudah dijaga oleh middleware 'auth')
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Periksa apakah role user ada di dalam daftar role yang diizinkan
        if (! in_array(Auth::user()->role, $roles, strict: true)) {
            // Akses ditolak: redirect ke dashboard sesuai role user yang sebenarnya
            return match (Auth::user()->role) {
                'admin'  => redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.'),
                'kasir'  => redirect()->route('kasir.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.'),
                default  => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}
