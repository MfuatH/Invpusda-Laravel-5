<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaporanRapat; // Import Model
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Untuk Auth Admin
use Carbon\Carbon; // Untuk tanggal

class LaporanRapatController extends Controller
{
    /**
     * Menampilkan daftar dokumen (Admin Page).
     * View: admin_page/documents/index.blade.php
     */
    public function index()
    {
        // Ambil data, urutkan terbaru, load relasi catering agar tidak berat
        $documents = LaporanRapat::with('catering')->latest()->paginate(10);

        return view('admin_page.documents.index', compact('documents'));
    }

    /**
     * Verifikasi Laporan (Update Status).
     */
    public function verify(Request $request, $id)
    {
        $laporan = LaporanRapat::findOrFail($id);

        // Update status menjadi verified
        $laporan->update([
            'status' => LaporanRapat::STATUS_VERIFIED,
            'verified_by' => Auth::id(), // ID Admin yang login
            'verified_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diverifikasi.');
    }

    /**
     * Download File Laporan.
     */
    public function download($id)
    {
        $laporan = LaporanRapat::findOrFail($id);

        if (!$laporan->file_laporan || !Storage::exists($laporan->file_laporan)) {
            return redirect()->back()->with('error', 'File fisik tidak ditemukan di server.');
        }

        // Download dengan nama asli user
        return Storage::download($laporan->file_laporan, $laporan->file_original_name);
    }

    /**
     * Hapus Laporan & File Fisik.
     */
    public function destroy($id)
    {
        $laporan = LaporanRapat::findOrFail($id);
        $filePath = $laporan->file_laporan;

        try {
            // Hapus Record DB
            $laporan->delete();

            // Hapus File Fisik
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}