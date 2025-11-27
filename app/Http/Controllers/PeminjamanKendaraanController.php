<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kendaraan;
use App\PeminjamanKendaraan;
use Carbon\Carbon;
use App\Services\FontteService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PeminjamanKendaraanController extends Controller
{
    // =========================================================================
    // FRONTEND: FORM REQUEST
    // =========================================================================
    public function create()
    {
        // Hanya tampilkan kendaraan yang statusnya 'available'
        $kendaraans = \App\Kendaraan::where('status', 'available')->get();
        return view('requests.kendaraan_create', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:50',
            'urgensi' => 'nullable|string|max:500',
            'kendaraan_id' => 'required|exists:kendaraan,id', 
            'tanggal_ambil' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_ambil'
        ]);

        // =====================================================================
        // [PERBAIKAN] CEK STATUS FISIK KENDARAAN (LOGIKA TAMBAHAN)
        // =====================================================================
        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        
        // Jika status mobil bukan 'available' (misal: unavailable/maintenance)
        if ($kendaraan->status !== 'available') {
            return redirect()->back()
                ->withInput()
                ->with('error', "Gagal! Kendaraan {$kendaraan->jenis} ({$kendaraan->plat_no}) saat ini TIDAK TERSEDIA (Status: {$kendaraan->status}).");
        }

        $start = Carbon::parse($request->input('tanggal_ambil'));
        $end = Carbon::parse($request->input('tanggal_kembali'));

        // 2. CEK JADWAL BENTROK
        $bentrok = PeminjamanKendaraan::where('kendaraan_id', $request->kendaraan_id)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'completed') 
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('tanggal_ambil', [$start, $end])
                      ->orWhereBetween('tanggal_kembali', [$start, $end])
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('tanggal_ambil', '<=', $start)
                            ->where('tanggal_kembali', '>=', $end);
                      });
            })
            ->exists();

        if ($bentrok) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Maaf, jadwal kendaraan {$kendaraan->jenis} ({$kendaraan->plat_no}) BENTROK/SUDAH DIPINJAM pada tanggal tersebut.");
        }

        // 3. SIMPAN DATA
        $data = $request->only(['nama','nip','no_hp','urgensi','kendaraan_id']);
        $data['tanggal_ambil'] = $start;
        $data['tanggal_kembali'] = $end;
        $data['status'] = 'pending';

        $peminjaman = PeminjamanKendaraan::create($data);

        // 4. KIRIM NOTIFIKASI WA KE ADMIN
        $admin = \App\User::whereIn('role', ['super_admin', 'admin_barang'])
                          ->whereNotNull('no_hp')
                          ->first();

        if ($admin && $admin->no_hp) {
            try {
                $this->sendWhatsAppNotification($peminjaman, 'new_request', null, $admin->no_hp);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Admin: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Permintaan berhasil dikirim. Menunggu persetujuan Admin.');
    }

    // =========================================================================
    // ADMIN: APPROVAL & MANAJEMEN
    // =========================================================================
    
    public function index()
    {
        $requests = PeminjamanKendaraan::with('kendaraan')->latest()->paginate(20);
        return view('admin_page.approvals.kendaraan', compact('requests'));
    }

    public function approve(Request $request, $id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        
        // Cek status mobil sebelum approve
        if ($peminjaman->kendaraan && $peminjaman->kendaraan->status !== 'available') {
            return redirect()->back()->with('error', 'Gagal Approve! Kendaraan ini statusnya sudah UNAVAILABLE.');
        }

        $peminjaman->update(['status' => 'approved']);

        // Update Status Kendaraan Jadi UNAVAILABLE
        if ($peminjaman->kendaraan) {
            $peminjaman->kendaraan->update(['status' => 'unavailable']);
        }

        if ($peminjaman->no_hp) {
            try {
                $this->sendWhatsAppNotification($peminjaman, 'approved', $request->note, $peminjaman->no_hp);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Approve: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Permintaan disetujui. Status kendaraan berubah menjadi UNAVAILABLE.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['note' => 'required']);

        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $peminjaman->update(['status' => 'rejected']);

        // Kembalikan status mobil jadi available (jaga-jaga)
        if ($peminjaman->kendaraan) {
            $peminjaman->kendaraan->update(['status' => 'available']);
        }

        if ($peminjaman->no_hp) {
            try {
                $this->sendWhatsAppNotification($peminjaman, 'rejected', $request->note, $peminjaman->no_hp);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Reject: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Permintaan ditolak dan notifikasi WA terkirim.');
    }

    public function complete(Request $request, $id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $peminjaman->update(['status' => 'completed']);

        // Kembalikan Status Kendaraan Jadi AVAILABLE
        if ($peminjaman->kendaraan) {
            $peminjaman->kendaraan->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Peminjaman selesai. Status kendaraan kembali AVAILABLE.');
    }

    // =========================================================================
    // FUNGSI PRIVAT & CRUD LAINNYA
    // =========================================================================
    
    private function sendWhatsAppNotification($peminjaman, $type, $additionalInfo = null, $targetNumber)
    {
        $fontte = app(FontteService::class);
        $nohp = preg_replace('/[\s\-\.\+]/', '', $targetNumber);
        if (substr($nohp, 0, 1) === '0') $nohp = '62' . substr($nohp, 1);
        elseif (!str_starts_with($nohp, '62')) $nohp = '62' . ltrim($nohp, '0');

        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian_indonesia.1252', 'ID');
        $formatted_ambil = Carbon::parse($peminjaman->tanggal_ambil)->formatLocalized('%A, %d %B %Y Jam %H:%M');
        $formatted_kembali = Carbon::parse($peminjaman->tanggal_kembali)->formatLocalized('%A, %d %B %Y Jam %H:%M');

        $msg = "";
        if ($type === 'new_request') {
            $msg = "🔔 *REQUEST KENDARAAN BARU*\nHalo Admin, ada pengajuan baru:\n👤 {$peminjaman->nama}\n🚗 {$peminjaman->kendaraan->jenis} ({$peminjaman->kendaraan->plat_no})\n📅 {$formatted_ambil}\nCek dashboard.";
        } elseif ($type === 'approved') {
            $msg = "✅ *PERMINTAAN DISETUJUI*\nHalo {$peminjaman->nama}, request mobil Anda disetujui.\nCatatan: $additionalInfo\nTerima kasih.";
        } elseif ($type === 'rejected') {
            $msg = "❌ *PERMINTAAN DITOLAK*\nHalo {$peminjaman->nama}, request mobil Anda ditolak.\nAlasan: $additionalInfo";
        }

        if (!empty($msg)) {
            $fontte->sendMessage($nohp, $msg);
        }
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
            'status' => 'required|string|in:available,unavailable,maintenance'
        ]);
        Kendaraan::create($request->all());
        return redirect()->back()->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function updateKendaraan(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $request->validate([
            'jenis' => 'required|string|max:100',
            'plat_no' => 'required|string|max:50|unique:kendaraan,plat_no,' . $kendaraan->id,
            'status' => 'required|in:available,unavailable,maintenance'
        ]);
        $kendaraan->update($request->all());
        return redirect()->back()->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroyKendaraan($id)
    {
        Kendaraan::destroy($id);
        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function show($id)
    {
        $item = PeminjamanKendaraan::findOrFail($id);
        return view('admin_page.approvals.kendaraan_show', compact('item'));
    }
}