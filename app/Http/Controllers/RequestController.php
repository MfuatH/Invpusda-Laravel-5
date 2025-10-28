<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Bidang;
use App\RequestBarang;
use App\RequestLinkZoom;

class RequestController extends Controller
{
    /**
     * Landing Page Utama
     */
    public function landingPage()
    {
        // Pastikan file view ada di: resources/views/landing_page.blade.php
        return view('landing_page');
    }

    /**
     * Form Permintaan Barang
     */
    public function createBarang()
    {
        $bidang = Bidang::pluck('nama', 'id');
        $items = Item::where('jumlah', '>', 0)->pluck('nama_barang', 'id');

        return view('requests.barang_create', compact('bidang', 'items'));
    }

    /**
     * Simpan Permintaan Barang
     */
    public function storeBarang(Request $request)
    {
        $this->validate($request, [
            'nama_pemohon'   => 'required|string|max:255',
            'bidang_id'      => 'required|exists:bidang,id',
            'no_hp'          => 'required|string|max:15',
            'item_id'        => 'required|exists:items,id',
            'jumlah_request' => 'required|integer|min:1',
        ]);

        RequestBarang::create([
            'nama_pemohon'   => $request->nama_pemohon,
            'bidang_id'      => $request->bidang_id,
            'nip'            => $request->nip,
            'no_hp'          => $request->no_hp,
            'item_id'        => $request->item_id,
            'jumlah_request' => $request->jumlah_request,
            'status'         => 'pending',
        ]);

        return redirect()->route('landing-page')
            ->with('success', 'Permintaan barang berhasil diajukan dan menunggu persetujuan.');
    }

    /**
     * Form Permintaan Link Zoom
     */
    public function createZoom()
    {
        $bidang = Bidang::pluck('nama', 'id');
        return view('requests.zoom_create', compact('bidang'));
    }

    /**
     * Simpan Permintaan Link Zoom
     */
    public function storeZoom(Request $request)
    {
        $this->validate($request, [
            'nama_pemohon'   => 'required|string|max:255',
            'bidang_id'      => 'required|exists:bidang,id',
            'no_hp'          => 'required|string|max:15',
            'nama_rapat'     => 'required|string|max:255',
            'jadwal_mulai'   => 'required|date',
            'jadwal_selesai' => 'nullable|date|after:jadwal_mulai',
        ]);

        RequestLinkZoom::create([
            'nama_pemohon'   => $request->nama_pemohon,
            'bidang_id'      => $request->bidang_id,
            'nip'            => $request->nip,
            'no_hp'          => $request->no_hp,
            'nama_rapat'     => $request->nama_rapat,
            'jadwal_mulai'   => $request->jadwal_mulai,
            'jadwal_selesai' => $request->jadwal_selesai,
            'keterangan'     => $request->keterangan,
            'status'         => 'pending',
        ]);

        return redirect()->route('landing-page')
            ->with('success', 'Permintaan Link Zoom berhasil diajukan dan menunggu persetujuan.');
    }
}
