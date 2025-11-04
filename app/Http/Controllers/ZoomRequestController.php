<?php

namespace App\Http\Controllers;

use App\RequestLinkZoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoomRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function index()
    {
        $requests = RequestLinkZoom::with('bidang')
            ->when(Auth::user()->role !== 'super_admin', function($query) {
                return $query->where('bidang_id', Auth::user()->bidang_id);
            })
            ->latest()
            ->paginate(10);

        return view('admin_page.approvals.zoom', compact('requests'));
    }

    public function approve(RequestLinkZoom $reqZoom)
    {
        if (
            Auth::user()->role === 'admin_barang' &&
            (!$reqZoom->bidang_id || Auth::user()->bidang_id !== $reqZoom->bidang_id)
        ) {
            abort(403, 'Unauthorized action.');
        }

        // Update status dan kirim notifikasi WA
        $reqZoom->update([
            'status' => 'approved',
            'link_zoom' => 'https://zoom.us/j/example', // Replace with actual Zoom link generation
            'approved_by' => Auth::id()
        ]);

        // Kirim notifikasi WA
        if ($reqZoom->no_hp) {
            try {
                $fontte = app(\App\Services\FontteService::class);

                // --- Normalisasi nomor HP ---
                $nohp = preg_replace('/[\s\-\.\+]/', '', $reqZoom->no_hp); // hapus spasi, +, titik, strip

                if (substr($nohp, 0, 2) === '62') {
                    // sudah benar
                } elseif (substr($nohp, 0, 1) === '0') {
                    $nohp = '62' . substr($nohp, 1);
                } elseif (!str_starts_with($nohp, '62')) {
                    $nohp = '62' . ltrim($nohp, '0');
                }

                // --- Isi pesan ---
                $msg = "[Permintaan Zoom Disetujui]\nPermintaan link Zoom Anda telah disetujui.\nNama Rapat: {$reqZoom->nama_rapat}\nTanggal: {$reqZoom->jadwal_mulai}\nLink: {$reqZoom->link_zoom}";

                // --- Kirim pesan ---
                $fontte->sendMessage($nohp, $msg);

            } catch (\Exception $e) {
                \Log::error('Gagal kirim WA: ' . $e->getMessage());
            }
        }

        return redirect()->route('zoom.requests.index')
            ->with('success', 'Permintaan link zoom berhasil disetujui.');
    }

    public function reject(Request $request, $reqZoomId)
    {
        // Validasi form alasan penolakan
        $this->validate($request, [
            'note' => 'required|string|max:255',
        ]);

        // Ambil data permintaan Zoom
        $reqZoom = RequestLinkZoom::findOrFail($reqZoomId);

        // Ubah status ke "rejected"
        $reqZoom->update(['status' => 'rejected']);

        // Pastikan locale untuk tanggal Indonesia
        setlocale(LC_TIME, 'id_ID.UTF-8');

        // Format tanggal kegiatan
        $tanggal = \Carbon\Carbon::parse($reqZoom->jadwal_mulai)
            ->formatLocalized('%d %B %Y %H:%M');

        // Buat pesan WA dinamis
        $pesan = "Halo {$reqZoom->nama_pemohon},\n\n"
            . "Permintaan link Zoom untuk kegiatan *{$reqZoom->nama_rapat}* "
            . "pada tanggal {$tanggal} telah *DITOLAK*.\n\n"
            . "Alasan penolakan: _{$request->note}_\n\n"
            . "Silakan hubungi admin jika ingin mengajukan ulang.";

        // Format nomor HP jadi internasional (62)
        $noHp = trim($reqZoom->no_hp);
        $noHp = preg_replace('/[^0-9]/', '', $noHp); // hanya angka

        if (substr($noHp, 0, 1) === '0') {
            $noHp = '62' . substr($noHp, 1);
        } elseif (substr($noHp, 0, 3) === '+62') {
            $noHp = substr($noHp, 1);
        } elseif (substr($noHp, 0, 2) !== '62') {
            $noHp = '62' . $noHp;
        }

        // Kirim pesan lewat FonnteService
        try {
            $fonnte = new \App\Services\FontteService();
            $fonnte->sendMessage($noHp, $pesan);
        } catch (\Exception $e) {
            // Kamu bisa log error kalau mau
            // \Log::error('Gagal kirim WA reject: '.$e->getMessage());
        }

        // Redirect ke halaman approval dengan pesan sukses
        return redirect()->route('admin_page.approvals.zoom')
            ->with('success', 'Request berhasil ditolak dan notifikasi telah dikirim ke pemohon.');
    }
}