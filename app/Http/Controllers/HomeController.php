<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin_barang'])) {
            return redirect()->route('dashboard.index');
        }
        return redirect()->route('landing-page');
    }

    private function getAdminData($user, $search = null)
    {
        $data = [
            'totalItems' => Item::count(),
            'pendingRequests' => $this->getPendingRequests($user),
            'pendingZoomRequests' => $this->getPendingZoomRequests($user),
            'totalBarangMasuk' => $this->getTotalBarangMasuk($user),
            'totalBarangKeluar' => $this->getTotalBarangKeluar($user),
            'stockChartData' => $this->getStockChartData($search),
        ];

        if ($user->role === 'super_admin') {
            $data['totalUsers'] = User::count();
        }

        return $data;
    }

    private function getUserData($user, $search = null)
    {
        return [
            'totalItems' => Item::count(),
            'pendingRequests' => ItemRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'chartDataUser' => $this->getUserChartData($search)
        ];
    }

    private function getPendingRequests($user)
    {
        $query = ItemRequest::where('status', 'pending');
        
        if ($user->role !== 'super_admin') {
            $query->whereHas('bidang', function ($q) use ($user) {
                $q->where('nama', $user->bidang);
            });
        }

        return $query->count();
    }

    private function getPendingZoomRequests($user)
    {
        $query = RequestLinkZoom::where('status', 'pending');
        
        if ($user->role !== 'super_admin') {
            $query->whereHas('bidang', function ($q) use ($user) {
                $q->where('nama', $user->bidang);
            });
        }

        return $query->count();
    }

    private function getTotalBarangMasuk($user)
    {
        $query = Transaction::where('tipe', 'masuk');
        
        if ($user->role !== 'super_admin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            });
        }

        return $query->count();
    }

    private function getTotalBarangKeluar($user)
    {
        $query = Transaction::where('tipe', 'keluar');
        
        if ($user->role !== 'super_admin') {
            $query->whereHas('request', function ($rq) use ($user) {
                $rq->whereHas('bidang', function ($bq) use ($user) {
                    $bq->where('nama', $user->bidang);
                });
            });
        }

        return $query->count();
    }

    private function getStockChartData($search = null)
    {
        $query = Item::where('jumlah', '<', 10)
            ->orderBy('jumlah', 'asc')
            ->orderBy('nama_barang');

        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        $items = $query->limit(10)->get();

        return [
            'labels' => $items->pluck('nama_barang'),
            'data' => $items->pluck('jumlah'),
            'satuan' => $items->pluck('satuan'),
        ];
    }

    private function getUserChartData($search = null)
    {
        $query = Item::where('jumlah', '>', 0)
            ->orderBy('nama_barang');

        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        $items = $query->limit(15)->get();

        return [
            'labels' => $items->pluck('nama_barang'),
            'data' => $items->pluck('jumlah'),
        ];
    }

    public function redirectHome()
    {
        if (auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin_barang'])) {
            return redirect()->route('dashboard.index');
        }

        return redirect()->route('landing-page');
    }

}