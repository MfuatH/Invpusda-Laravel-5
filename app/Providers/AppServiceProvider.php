<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Catering;
use App\PeminjamanKendaraan; // [PENTING] Import Model PeminjamanKendaraan

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);

        // View Composer: Jalankan logika ini untuk SEMUA view ('*')
        View::composer('*', function ($view) {
            // Hanya hitung jika user sedang login
            if (Auth::check()) {
                $user = Auth::user();

                // 1. Hitung Permintaan Barang
                $totalRequests = ItemRequest::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();

                // 2. Hitung Permintaan Zoom
                $totalZoomRequests = RequestLinkZoom::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();

                // 3. Hitung Permintaan Catering
                $totalCateringRequests = 0;
                // Cek apakah tabel catering ada & user punya akses
                if (class_exists(Catering::class)) { 
                     // Validasi sederhana agar tidak error jika tabel belum dimigrasi
                     try {
                        $queryCatering = Catering::where('status', 'pending');
                        if ($user->role === 'admin_barang' && $user->bidang_id && Schema::hasColumn('catering', 'bidang_id')) {
                            $queryCatering->where('bidang_id', $user->bidang_id);
                        }
                        $totalCateringRequests = $queryCatering->count();
                     } catch (\Exception $e) {
                        $totalCateringRequests = 0;
                     }
                }

                // 4. [BARU] Hitung Permintaan Kendaraan (Mobil)
                $totalKendaraanRequests = 0;
                if (class_exists(PeminjamanKendaraan::class)) {
                    // Logika: Semua admin bisa melihat request, atau filter sesuai kebutuhan
                    // Jika perlu filter bidang, tambahkan logic when() seperti di atas
                    $totalKendaraanRequests = PeminjamanKendaraan::where('status', 'pending')->count();
                }

                // Kirim variabel 'notifCounts' ke semua View
                $view->with('notifCounts', [
                    'requests'  => $totalRequests,
                    'zoom'      => $totalZoomRequests,
                    'catering'  => $totalCateringRequests,
                    'kendaraan' => $totalKendaraanRequests, // [BARU] Masukkan ke array
                ]);
            }
        });
    }

    public function register()
    {
        //
    }
}