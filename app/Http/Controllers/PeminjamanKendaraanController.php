<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kendaraan;
use App\PeminjamanKendaraan;
use Carbon\Carbon;

class PeminjamanKendaraanController extends Controller
{
    public function create()
    {
        $kendaraans = \App\Kendaraan::all();
        return view('requests.kendaraan_create', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:50',
            'urgensi' => 'nullable|string|max:500',
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'tanggal_ambil' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_ambil'
        ]);

        $data = $request->only(['nama','nip','no_hp','urgensi','kendaraan_id']);
        $data['tanggal_ambil'] = Carbon::parse($request->input('tanggal_ambil'));
        $data['tanggal_kembali'] = Carbon::parse($request->input('tanggal_kembali'));
        $data['status'] = 'pending';

        PeminjamanKendaraan::create($data);

        return redirect()->back()->with('success', 'Permintaan peminjaman kendaraan berhasil dikirim.');
    }

    // Admin: list
    public function index()
    {
        $requests = PeminjamanKendaraan::latest()->paginate(20);
        
        return view('admin_page.approvals.kendaraan', compact('requests'));
    }

    public function show($id)
    {
        $item = PeminjamanKendaraan::findOrFail($id);
        return view('admin_page.approvals.kendaraan_show', compact('item'));
    }

    public function listKendaraan()
    {
        $kendaraans = Kendaraan::latest()->paginate(10);
        return view('admin_page.kendaraan.index', compact('kendaraans'));
    }

    public function storeKendaraan(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:100',
            'plat_no' => 'required|string|max:50|unique:kendaraan,plat_no',
            'status' => 'required|string|in:available,unavailable'
        ]);

        \App\Kendaraan::create($request->only(['jenis', 'plat_no', 'status']));

        return redirect()->back()->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function updateKendaraan(Request $request, $id)
    {
        $kendaraan = \App\Kendaraan::findOrFail($id);

        $request->validate([
            'jenis' => 'required|string|max:100',
            'plat_no' => 'required|string|max:50|unique:kendaraan,plat_no,' . $kendaraan->id,
            'status' => 'required|in:available,unavailable'
        ]);

        $kendaraan->update($request->only(['jenis', 'plat_no', 'status']));

        return redirect()->back()->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroyKendaraan($id)
    {
        $kendaraan = \App\Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus.');
    }
}