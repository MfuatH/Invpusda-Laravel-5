<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PeminjamanKendaraan;
use Carbon\Carbon;

class PeminjamanKendaraanController extends Controller
{
    public function create()
    {
        return view('requests.kendaraan_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:50',
            'urgensi' => 'nullable|string|max:500',
            'tanggal_ambil' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_ambil',
            'plat_no' => 'nullable|string|max:50'
        ]);

        $data = $request->only(['nama','nip','no_hp','urgensi','plat_no']);
        $data['tanggal_ambil'] = Carbon::parse($request->input('tanggal_ambil'));
        $data['tanggal_kembali'] = Carbon::parse($request->input('tanggal_kembali'));
        $data['status'] = 'pending';

        PeminjamanKendaraan::create($data);

        return redirect()->back()->with('success', 'Permintaan peminjaman kendaraan berhasil dikirim.');
    }

    // Admin: list
    public function index()
    {
        $this->middleware(['auth','role:super_admin,admin_barang']);
        $items = PeminjamanKendaraan::latest()->paginate(20);
        return view('admin_page.approvals.kendaraan_index', compact('items'));
    }

    public function show($id)
    {
        $this->middleware(['auth','role:super_admin,admin_barang']);
        $item = PeminjamanKendaraan::findOrFail($id);
        return view('admin_page.approvals.kendaraan_show', compact('item'));
    }
}
