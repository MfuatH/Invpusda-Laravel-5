<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Catering; // PENTING: TAMBAHKAN MODEL CATERING DI SINI

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // View Composer hanya aktif untuk user yang login
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Ambil jumlah permintaan barang
                $totalRequests = ItemRequest::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();

                // Ambil jumlah permintaan zoom
                $totalZoomRequests = RequestLinkZoom::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();

                // ==========================================================
                // --- TAMBAHKAN BLOK INI UNTUK MENGHITUNG CATERING ---
                // ==========================================================
                $totalCateringRequests = Catering::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        // Catatan: Ini mengasumsikan tabel 'caterings' Anda
                        // juga memiliki kolom 'bidang_id'.
                        // Jika tidak, hapus blok 'when()' ini.
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();
                // ==========================================================


                // Kirim ke semua view yang pakai layout admin
                $view->with('notifCounts', [
                    'requests' => $totalRequests,
                    'zoom' => $totalZoomRequests,
                    'catering' => $totalCateringRequests, // <-- TAMBAHKAN INI
                ]);
            }
        });
    }

    public function register() {}
}