<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
                    ->when(
                        // Only apply bidang filter when the user is admin_barang,
                        // has a bidang_id, AND the catering table actually has the column
                        $user->role === 'admin_barang' && $user->bidang_id && Schema::hasColumn((new Catering)->getTable(), 'bidang_id'),
                        function ($query) use ($user) {
                            $query->where('bidang_id', $user->bidang_id);
                        }
                    )
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