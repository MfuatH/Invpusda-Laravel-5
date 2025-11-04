<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin_barang');

        // 🔹 Tambahkan View Composer agar notif muncul di semua halaman admin
        View::composer('*', function ($view) {
            $user = Auth::user();
            if ($user) {
                $totalRequests = $this->getPendingRequests($user);
                $totalZoomRequests = $this->getPendingZoomRequests($user);

                // Kirim data global ke semua view
                $view->with('notifCounts', [
                    'requests' => $totalRequests,
                    'zoom' => $totalZoomRequests,
                ]);
            }
        });
    }

    public function index()
    {
        $user = Auth::user();

        // Hitung total barang
        $totalItems = Item::count();

        // Hitung request pending barang & zoom
        $totalRequests = $this->getPendingRequests($user);
        $totalZoomRequests = $this->getPendingZoomRequests($user);

        // Ambil transaksi terbaru
        $recentTransactions = $this->getRecentTransactions($user);

        // Data utama dashboard
        $data = [
            'totalItems'         => $totalItems,
            'totalRequests'      => $totalRequests,
            'totalZoomRequests'  => $totalZoomRequests,
            'recentTransactions' => $recentTransactions,
        ];

        // Tambahkan total user jika super_admin
        if ($user->role === 'super_admin') {
            $data['totalUsers'] = User::count();
        }

        return view('admin_page.dashboard', compact('data'));
    }

    /**
     * Hitung jumlah permintaan barang pending
     */
    private function getPendingRequests($user)
    {
        $query = ItemRequest::where('status', 'pending');

        if ($user->role === 'admin_barang' && !empty($user->bidang_id)) {
            $query->where('bidang_id', $user->bidang_id);
        }

        return $query->count();
    }

    /**
     * Hitung jumlah permintaan link zoom pending
     */
    private function getPendingZoomRequests($user)
    {
        $query = RequestLinkZoom::where('status', 'pending');

        if ($user->role === 'admin_barang' && !empty($user->bidang_id)) {
            $query->where('bidang_id', $user->bidang_id);
        }

        return $query->count();
    }

    /**
     * Ambil 5 transaksi terbaru
     */
    private function getRecentTransactions($user)
    {
        $query = Transaction::with(['item', 'user'])
            ->latest()
            ->limit(5);

        if ($user->role === 'admin_barang' && !empty($user->bidang_id)) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('bidang_id', $user->bidang_id);
            });
        }

        return $query->get();
    }
}
