<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin_barang');
    }

    public function index()
    {
        $user = Auth::user();

        // Base query untuk menghitung item (tidak terikat bidang)
        $totalItems = Item::count();

        // Hitung total request sesuai bidang
        $totalRequests = $this->getPendingRequests($user);
        $totalZoomRequests = $this->getPendingZoomRequests($user);

        // Ambil transaksi terbaru
        $recentTransactions = $this->getRecentTransactions($user);

        $data = [
            'totalItems' => $totalItems,
            'totalRequests' => $totalRequests,
            'totalZoomRequests' => $totalZoomRequests,
            'recentTransactions' => $recentTransactions,
        ];

        if ($user->role === 'super_admin') {
            $data['totalUsers'] = User::count();
        }

        return view('admin_page.dashboard', compact('data'));
    }

    private function getPendingRequests($user)
    {
        $query = ItemRequest::where('status', 'pending');

        // Jika admin_barang, filter berdasarkan bidang_id miliknya
        if ($user->role === 'admin_barang' && $user->bidang_id) {
            $query->where('bidang_id', $user->bidang_id);
        }

        return $query->count();
    }

    private function getPendingZoomRequests($user)
    {
        $query = RequestLinkZoom::where('status', 'pending');

        if ($user->role === 'admin_barang' && $user->bidang_id) {
            $query->where('bidang_id', $user->bidang_id);
        }

        return $query->count();
    }

    private function getRecentTransactions($user)
    {
        $query = Transaction::with(['item', 'user']);

        // Filter transaksi berdasarkan bidang user (via relasi user)
        if ($user->role === 'admin_barang' && $user->bidang_id) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('bidang_id', $user->bidang_id);
            });
        }

        return $query->latest()->limit(5)->get();
    }
}
