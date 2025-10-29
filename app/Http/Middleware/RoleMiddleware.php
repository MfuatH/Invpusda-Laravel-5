<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $roles  Peran yang diizinkan (dipisahkan koma, misal: 'super_admin,admin_barang')
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        // 1. Cek Autentikasi
        // Jika pengguna belum login, alihkan ke halaman login.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Memisahkan string peran menjadi array (misalnya "super_admin,admin_barang" menjadi ['super_admin', 'admin_barang'])
        $requiredRoles = is_array($roles) ? $roles : explode(',', $roles);
        
        // Menghilangkan spasi pada setiap nama peran
        $requiredRoles = array_map('trim', $requiredRoles);

        // 2. Cek Peran
        // Periksa apakah peran pengguna (user->role) ada di dalam daftar peran yang diizinkan ($requiredRoles).
        if (!in_array($user->role, $requiredRoles)) {
            // Jika peran tidak diizinkan, hentikan eksekusi dan kirim respons 403 Forbidden.
            abort(403, 'Akses Dilarang. Anda tidak memiliki izin (' . strtoupper($user->role) . ') untuk mengakses halaman ini.');
        }

        // 3. Lanjutkan Request
        // Jika pengguna terautentikasi dan perannya sesuai, izinkan request untuk dilanjutkan.
        return $next($request);
    }
}