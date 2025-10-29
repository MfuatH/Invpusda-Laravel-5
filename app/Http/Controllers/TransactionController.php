<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function index()
    {
        $transactions = Transaction::with(['item', 'user', 'request'])
            ->when(Auth::user()->role !== 'super_admin', function($query) {
                return $query->whereHas('user', function($q) {
                    $q->where('bidang', Auth::user()->bidang);
                });
            })
            ->latest()
            ->paginate(15);

        return view('transaksi.index', compact('transactions'));
    }
}