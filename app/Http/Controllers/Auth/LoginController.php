<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Mengatur autentikasi user dan redirect sesuai role.
    |
    */

    use AuthenticatesUsers;

    /**
     * Lokasi redirect default setelah login (fallback).
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override bawaan setelah user berhasil login.
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect berdasarkan role
        if (in_array($user->role, ['super_admin', 'admin_barang'])) {
            return redirect()->route('dashboard.index');
        }

        // Jika role lain (misalnya user biasa), ke landing page
        return redirect()->route('landing-page');
    }

    /**
     * Logout dan hapus session dengan aman
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Kembali ke halaman utama
        return redirect('/');
    }
}
