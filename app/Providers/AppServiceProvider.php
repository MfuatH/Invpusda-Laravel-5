<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\ItemRequest;
use App\RequestLinkZoom;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // View Composer hanya aktif untuk user yang login
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Ambil jumlah permintaan barang & zoom pending
                $totalRequests = ItemRequest::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();

                $totalZoomRequests = RequestLinkZoom::where('status', 'pending')
                    ->when($user->role === 'admin_barang' && $user->bidang_id, function ($query) use ($user) {
                        $query->where('bidang_id', $user->bidang_id);
                    })
                    ->count();

                // Kirim ke semua view yang pakai layout admin
                $view->with('notifCounts', [
                    'requests' => $totalRequests,
                    'zoom' => $totalZoomRequests,
                ]);
            }
        });
    }

    public function register() {}
}
