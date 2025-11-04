<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\ItemRequest;
use App\RequestLinkZoom;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Bagikan data notifikasi ke semua view
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Query jumlah request pending (barang dan zoom)
                $totalRequests = ItemRequest::where('status', 'pending');
                $totalZoomRequests = RequestLinkZoom::where('status', 'pending');

                // Jika role admin_barang, filter berdasarkan bidang
                if ($user->role === 'admin_barang' && !empty($user->bidang_id)) {
                    $totalRequests->where('bidang_id', $user->bidang_id);
                    $totalZoomRequests->where('bidang_id', $user->bidang_id);
                }

                $data = [
                    'totalRequests' => $totalRequests->count(),
                    'totalZoomRequests' => $totalZoomRequests->count(),
                ];

                $view->with('data', $data);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
