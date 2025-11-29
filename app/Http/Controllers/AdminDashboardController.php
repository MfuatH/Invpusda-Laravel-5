<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Catering;
use App\LaporanRapat;
use App\Transaction;
use App\User;
// [BARU] Import Model Peminjaman Kendaraan
use App\PeminjamanKendaraan; 
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

        // 1. Hitung total barang
        $totalItems = Item::count();

        // 2. Hitung total permintaan pending
        $totalRequests = $this->getPendingRequests($user);
        $totalZoomRequests = $this->getPendingZoomRequests($user);
        $totalCateringRequests = $this->getPendingCateringRequests($user);
        
        // [BARU] Hitung total permintaan kendaraan pending
        $totalKendaraanRequests = $this->getPendingKendaraanRequests($user);

        // 3. Ambil 5 transaksi terbaru
        $recentTransactions = $this->getRecentTransactions($user);

        // 4. Ambil 5 barang terbaru
        $recentItems = Item::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5. Ambil 5 dokumen (laporan) terbaru
        $recentDocuments = $this->getRecentDocuments($user);

        // 6. Kumpulkan semua data untuk dashboard
        $data = [
            'totalItems'             => $totalItems,
            'totalRequests'          => $totalRequests,
            'totalZoomRequests'      => $totalZoomRequests,
            'totalCateringRequests'  => $totalCateringRequests,
            'totalKendaraanRequests' => $totalKendaraanRequests, // [BARU] Masukkan ke array data
            'recentTransactions'     => $recentTransactions,
            'recentItems'            => $recentItems,
            'recentDocuments'        => $recentDocuments,
        ];

        // 7. Tambahkan total pengguna jika super_admin
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
     * Hitung jumlah permintaan catering pending
     */
    private function getPendingCateringRequests($user)
    {
        $query = Catering::where('status', 'pending');
        return $query->count();
    }

    /**
     * [BARU] Hitung jumlah permintaan kendaraan pending
     */
    private function getPendingKendaraanRequests($user)
    {
        // Asumsi: Semua admin bisa melihat permintaan kendaraan, 
        // atau sesuaikan filter jika hanya admin tertentu.
        return PeminjamanKendaraan::where('status', 'pending')->count();
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

    /**
     * Ambil 5 dokumen (laporan rapat) terbaru
     */
    private function getRecentDocuments($user)
    {
        $query = LaporanRapat::orderBy('created_at', 'desc')
            ->limit(5);
        return $query->get();
    }
}