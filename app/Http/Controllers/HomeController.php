<?php

namespace App\Http\Controllers;

// 1. Salin semua 'use' statements dari DashboardController Anda
use App\Item;
use App\ItemRequest;
use App\RequestLinkZoom;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        if (in_array($user->role, ['super_admin', 'admin_barang'])) {
            $totalItems = Item::count();

            if ($user->role === 'super_admin') {
                $pendingRequests = ItemRequest::where('status', 'pending')->count();
                $pendingZoomRequests = RequestLinkZoom::where('status', 'pending')->count();
                $totalBarangMasuk = Transaction::where('tipe', 'masuk')->count();
                $totalBarangKeluar = Transaction::where('tipe', 'keluar')->count();
            } else {
                $pendingRequests = ItemRequest::where('status', 'pending')
                    ->whereHas('bidang', function ($q) use ($user) {
                        $q->where('nama', $user->bidang);
                    })
                    ->count();

                $pendingZoomRequests = RequestLinkZoom::where('status', 'pending')
                    ->whereHas('bidang', function ($q) use ($user) {
                        $q->where('nama', $user->bidang);
                    })
                    ->count();

                $totalBarangMasuk = Transaction::where('tipe', 'masuk')
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('bidang', $user->bidang);
                    })
                    ->count();

                $totalBarangKeluar = Transaction::where('tipe', 'keluar')
                    ->whereHas('request', function ($rq) use ($user) {
                        $rq->whereHas('bidang', function ($bq) use ($user) {
                            $bq->where('nama', $user->bidang);
                        });
                    })
                    ->count();
            }

            $stockQuery = Item::where('jumlah', '<', 10)->orderBy('jumlah', 'asc')->orderBy('nama_barang');
            if ($search) {
                $stockQuery->where('nama_barang', 'like', '%' . $search . '%');
            }
            $stockItems = $stockQuery->limit(10)->get();
            $stockChartData = [
                'labels' => $stockItems->pluck('nama_barang'),
                'data' => $stockItems->pluck('jumlah'),
                'satuan' => $stockItems->pluck('satuan'),
            ];

            $viewData = compact('totalItems', 'pendingRequests', 'pendingZoomRequests', 'totalBarangMasuk', 'totalBarangKeluar', 'stockChartData', 'search');
            if ($user->role === 'super_admin') {
                $viewData['totalUsers'] = User::count();
            }

            return view('dashboard', $viewData);
        }

        if ($user->role === 'user') {
            $totalItems = Item::count();
            $pendingRequests = ItemRequest::where('user_id', $user->id)->where('status', 'pending')->count();
            $availableQuery = Item::where('jumlah', '>', 0)->orderBy('nama_barang');
            if ($search) {
                $availableQuery->where('nama_barang', 'like', '%' . $search . '%');
            }
            $availableItems = $availableQuery->limit(15)->get();
            
            $chartDataUser = [
                'labels' => $availableItems->pluck('nama_barang'),
                'data' => $availableItems->pluck('jumlah'),
            ];
            
            return view('dashboard', compact('totalItems', 'pendingRequests', 'chartDataUser', 'search'));
        }
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        if (in_array($user->role, ['super_admin', 'admin_barang'])) {
            $stockQuery = Item::where('jumlah', '<', 10)->orderBy('jumlah', 'asc')->orderBy('nama_barang');
            
            if (!empty($search) && trim($search) !== '') {
                $stockQuery->where('nama_barang', 'like', '%' . trim($search) . '%');
            }
            
            $stockItems = $stockQuery->limit(10)->get();
            
            return response()->json([
                'labels' => $stockItems->pluck('nama_barang'),
                'data' => $stockItems->pluck('jumlah'),
                'satuan' => $stockItems->pluck('satuan'),
            ]);
        }

        if ($user->role === 'user') {
            $availableQuery = Item::where('jumlah', '>', 0)->orderBy('nama_barang');
            
            if (!empty($search) && trim($search) !== '') {
                $availableQuery->where('nama_barang', 'like', '%' . trim($search) . '%');
            }
            
            $availableItems = $availableQuery->limit(15)->get();
            
            return response()->json([
                'labels' => $availableItems->pluck('nama_barang'),
                'data' => $availableItems->pluck('jumlah'),
            ]);
        }

        return response()->json(['labels' => [], 'data' => []]);
    }
}