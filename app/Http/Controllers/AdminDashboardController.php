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
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'totalItems' => Item::count(),
            'totalRequests' => $this->getPendingRequests(),
            'totalZoomRequests' => $this->getPendingZoomRequests(),
            'recentTransactions' => $this->getRecentTransactions()
        ];

        if ($user->role === 'super_admin') {
            $data['totalUsers'] = User::count();
        }

        return view('admin_page.dashboard', compact('data'));
    }

    private function getPendingRequests()
    {
        $query = ItemRequest::where('status', 'pending');
        
        if (Auth::user()->role !== 'super_admin') {
            $query->whereHas('bidang', function($q) {
                $q->where('nama', Auth::user()->bidang);
            });
        }

        return $query->count();
    }

    private function getPendingZoomRequests()
    {
        $query = RequestLinkZoom::where('status', 'pending');
        
        if (Auth::user()->role !== 'super_admin') {
            $query->whereHas('bidang', function($q) {
                $q->where('nama', Auth::user()->bidang);
            });
        }

        return $query->count();
    }

    private function getRecentTransactions()
    {
        $query = Transaction::with(['item', 'user']);
        
        if (Auth::user()->role !== 'super_admin') {
            $query->whereHas('user', function($q) {
                $q->where('bidang', Auth::user()->bidang);
            });
        }

        return $query->latest()->limit(5)->get();
    }
}