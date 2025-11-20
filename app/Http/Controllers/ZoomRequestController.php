<?php

namespace App\Http\Controllers;

use App\RequestLinkZoom; // Pastikan Model ini benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\FontteService; // Pastikan Service ini ada
use Illuminate\Support\Facades\Log;

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

    /**
     * Menyetujui Permintaan Zoom
     */
    public function approve(Request $request, $id)
    {
        // 1. VALIDASI INPUT (PENTING: Agar link tidak kosong)
        $request->validate([
            'link_zoom' => 'required|string' 
        ]);

        // 2. AMBIL DATA
        $reqZoom = RequestLinkZoom::findOrFail($id);

        // 3. CEK OTORISASI (Admin Barang hanya boleh bidangnya sendiri)
        if (
            Auth::user()->role === 'admin_barang' &&
            (!$reqZoom->bidang_id || Auth::user()->bidang_id !== $reqZoom->bidang_id)
        ) {
            abort(403, 'Unauthorized action.');
        }

        // 4. UPDATE DATABASE
        // Link diambil dari input form ($request->link_zoom)
        $reqZoom->update([
            'status'      => 'approved',
            'link_zoom'   => $request->link_zoom, 
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        // 5. KIRIM NOTIFIKASI WA
        if ($reqZoom->no_hp) {
            try {
                $this->sendWhatsAppNotification($reqZoom, 'approved', $request->link_zoom);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Approve: ' . $e->getMessage());
            }
        }

        return redirect()->route('zoom.requests.index')
            ->with('success', 'Permintaan link zoom berhasil disetujui dan link telah tersimpan.');
    }

    /**
     * Menolak Permintaan Zoom
     */
    public function reject(Request $request, $id)
    {
        // 1. VALIDASI CATATAN
        $request->validate([
            'note' => 'required|string|max:255',
        ]);

        // 2. AMBIL DATA
        $reqZoom = RequestLinkZoom::findOrFail($id);

        // 3. UPDATE DATABASE
        // Simpan alasan penolakan ke kolom rejection_note (Pastikan kolom ini ada di DB)
        $reqZoom->update([
            'status'         => 'rejected',
            'rejection_note' => $request->note, // Simpan alasan ke DB
            'approved_by'    => Auth::id(),
            'approved_at'    => now()
        ]);

        // 4. KIRIM NOTIFIKASI WA
        if ($reqZoom->no_hp) {
            try {
                $this->sendWhatsAppNotification($reqZoom, 'rejected', $request->note);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Reject: ' . $e->getMessage());
            }
        }

        return redirect()->route('zoom.requests.index')
            ->with('success', 'Request berhasil ditolak dan notifikasi telah dikirim ke pemohon.');
    }

    /**
     * FUNGSI PRIVAT UNTUK MENGIRIM WA
     * (Digunakan oleh Approve dan Reject agar kodingan lebih rapi)
     */
    private function sendWhatsAppNotification($reqZoom, $type, $additionalInfo)
    {
        $fontte = app(FontteService::class);

        // --- A. Normalisasi Nomor HP ---
        $nohp = preg_replace('/[\s\-\.\+]/', '', $reqZoom->no_hp); // Hapus karakter aneh
        
        if (substr($nohp, 0, 2) === '62') {
            // Sudah format 62
        } elseif (substr($nohp, 0, 1) === '0') {
            $nohp = '62' . substr($nohp, 1);
        } elseif (!str_starts_with($nohp, '62')) {
            $nohp = '62' . ltrim($nohp, '0');
        }

        // --- B. Siapkan Data Format Tanggal ---
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian_indonesia.1252', 'ID');
        $formatted_mulai = Carbon::parse($reqZoom->jadwal_mulai)->formatLocalized('%d %B %Y %H:%M');
        $formatted_selesai = '-';
        if ($reqZoom->jadwal_selesai) {
            $formatted_selesai = Carbon::parse($reqZoom->jadwal_selesai)->formatLocalized('%d %B %Y %H:%M');
        }
        
        // --- C. Logika Pesan Berdasarkan Tipe ---
        $msg = "";

        if ($type === 'approved') {
            // -- Cek Template dari Bidang --
            $bidang = $reqZoom->bidang;
            
            if ($bidang && !empty($bidang->pesan_template)) {
                $template = $bidang->pesan_template;

                // Mapping Placeholder
                $replacements = [
                    '{nama_pemohon}'    => $reqZoom->nama_pemohon,
                    '{nip}'             => $reqZoom->nip ?? '-',
                    '{nama_rapat}'      => $reqZoom->nama_rapat,
                    '{jadwal_mulai}'    => $formatted_mulai,
                    '{jadwal_selesai}'  => $formatted_selesai,
                    '{link_zoom}'       => $additionalInfo, // Link Zoom dari parameter
                    '@nama'             => $reqZoom->nama_pemohon,
                    '@kegiatan'         => $reqZoom->nama_rapat,
                    '@tanggal'          => $formatted_mulai,
                    '@link'             => $additionalInfo,
                ];

                $msg = strtr($template, $replacements);
            } else {
                // Template Default Approve
                $msg = "*[Permintaan Zoom Disetujui]*\n\n" .
                       "Halo {$reqZoom->nama_pemohon},\n" .
                       "Permintaan link Zoom Anda telah disetujui.\n\n" .
                       "Nama Rapat: {$reqZoom->nama_rapat}\n" .
                       "Jadwal: {$formatted_mulai}\n" .
                       "Link Zoom: {$additionalInfo}\n\n" . 
                       "Terima kasih.";
            }

        } else {
            // -- Template Default Reject --
            $msg = "*[Permintaan Zoom Ditolak]*\n\n" .
                   "Halo {$reqZoom->nama_pemohon},\n" .
                   "Permintaan link Zoom untuk kegiatan *{$reqZoom->nama_rapat}* " .
                   "pada tanggal {$formatted_mulai} telah *DITOLAK*.\n\n" .
                   "Alasan: _{$additionalInfo}_\n\n" . // Alasan penolakan dari parameter
                   "Silakan hubungi admin jika ingin mengajukan ulang.";
        }

        // --- D. Eksekusi Kirim ---
        $fontte->sendMessage($nohp, $msg);
    }
}