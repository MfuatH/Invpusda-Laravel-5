<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bidang; // model bidang
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ================================
    // HALAMAN MASTER PESAN
    // ================================
    public function templateIndex()
    {
        // semua bidang untuk super_admin, hanya bidang sendiri untuk admin_barang
        if (Auth::user()->role === 'super_admin') {
            $bidangs = Bidang::orderBy('nama')->get();
        } else {
            $bidangs = Bidang::where('id', Auth::user()->bidang_id)->get();
        }

        // semua data template (ambil dari tabel bidang)
        $templates = Bidang::select('id', 'nama', 'pesan_template')->get();

        return view('admin_page.settings.master_pesan', compact('bidangs', 'templates'));
    }

    // ================================
    // UPDATE / SIMPAN TEMPLATE PESAN
    // ================================
    public function updateTemplate(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidang,id',
            'pesan_template' => 'required|string|max:5000',
        ]);

        $bidang = Bidang::findOrFail($request->bidang_id);
        $bidang->pesan_template = $request->pesan_template;
        $bidang->save();

        return redirect()->route('template.index')->with('success', 'Template pesan berhasil diperbarui!');
    }

    // (opsional) Jika ingin halaman response tambahan
    public function responseIndex()
    {
        return view('admin_page.settings.response');
    }
}
