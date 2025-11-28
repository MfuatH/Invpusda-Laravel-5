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
        $this->cekOtomatisKembali();
        
        $kendaraans = \App\Kendaraan::where('status', 'available')->get();
        return view('requests.kendaraan_create', compact('kendaraans'));
    }

    private function cekOtomatisKembali()
    {
        $expiredLoans = PeminjamanKendaraan::where('status', 'approved')
                        ->where('tanggal_kembali', '<=', Carbon::now())
                        ->with('kendaraan')
                        ->get();

        if ($expiredLoans->count() > 0) {
            foreach ($expiredLoans as $loan) {
                $loan->update(['status' => 'completed']);

                if ($loan->kendaraan) {
                    $loan->kendaraan->update(['status' => 'available']);
                }
            }
        }
    }

    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:50',
            'urgensi' => 'nullable|string|max:500',
            'kendaraan_id' => 'required|exists:kendaraan,id', 
            'tanggal_ambil' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_ambil'
        ]);

        $start = Carbon::parse($request->input('tanggal_ambil'));
        $end = Carbon::parse($request->input('tanggal_kembali'));

        // ---------------------------------------------------------------------
        // [FITUR 1] CEK BATAS MAKSIMAL DURASI PEMINJAMAN (2 HARI / 48 JAM)
        // ---------------------------------------------------------------------
        if ($start->diffInHours($end) > 48) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal! Durasi peminjaman melebihi batas. Maksimal hanya boleh 2 Hari (48 Jam).');
        }

        // ---------------------------------------------------------------------
        // [FITUR 2] CEK JEDA / COOLDOWN 2 HARI (Identifikasi via NIP/HP)
        // ---------------------------------------------------------------------
        $identifierColumn = $request->filled('nip') ? 'nip' : 'no_hp';
        $identifierValue  = $request->filled('nip') ? $request->nip : $request->no_hp;

        // Cari peminjaman terakhir user ini yang TIDAK DITOLAK
        $lastLoan = PeminjamanKendaraan::where($identifierColumn, $identifierValue)
            ->where('status', '!=', 'rejected')
            ->orderBy('tanggal_kembali', 'desc') // Ambil yang paling terakhir
            ->first();

        if ($lastLoan) {
            $lastReturnDate = Carbon::parse($lastLoan->tanggal_kembali);

            // ATURAN JEDA: Cooldown 2 hari berdasarkan TANGGAL (ignore time-of-day)
            // Contoh: jika pengembalian terakhir ber-tanggal 2025-11-01 (jam berapapun),
            // maka peminjam baru boleh meminjam lagi mulai tanggal 2025-11-03.
            $allowedDateString = $lastReturnDate->copy()->addDays(2)->toDateString(); // 'YYYY-MM-DD'

            // Ambil tanggal yang diminta (date-only)
            $requestedDateString = $start->toDateString();

            // Jika tanggal permintaan masih kurang dari tanggal yang diizinkan -> tolak
            if ($requestedDateString < $allowedDateString) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Gagal! Anda harus menunggu jeda (cooldown) 2 hari setelah peminjaman terakhir. Anda baru bisa meminjam lagi mulai tanggal: " . Carbon::parse($allowedDateString)->format('d/m/Y'));
            }
        }

        // ---------------------------------------------------------------------
        // [FITUR 3] CEK STATUS FISIK KENDARAAN (SAKLAR)
        // ---------------------------------------------------------------------
        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        if ($kendaraan->status !== 'available') {
            return redirect()->back()
                ->withInput()
                ->with('error', "Gagal! Kendaraan {$kendaraan->jenis} ({$kendaraan->plat_no}) saat ini TIDAK TERSEDIA (Status: {$kendaraan->status}).");
        }

        // ---------------------------------------------------------------------
        // 4. CEK JADWAL BENTROK
        // ---------------------------------------------------------------------
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
                ->with('error', "Maaf, jadwal kendaraan bentrok dengan peminjaman lain.");
        }

        // 5. SIMPAN DATA
        $data = $request->only(['nama','nip','no_hp','urgensi','kendaraan_id']);
        $data['tanggal_ambil'] = $start;
        $data['tanggal_kembali'] = $end;
        $data['status'] = 'pending';

        $peminjaman = PeminjamanKendaraan::create($data);

        // 6. KIRIM NOTIFIKASI WA KE ADMIN
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
        $this->cekOtomatisKembali();

        $requests = PeminjamanKendaraan::with('kendaraan')->latest()->paginate(20);
        return view('admin_page.approvals.kendaraan', compact('requests'));
    }

    public function approve(Request $request, $id)
    {
        $peminjaman = PeminjamanKendaraan::findOrFail($id);
        $kendaraan = $peminjaman->kendaraan;

        if ($kendaraan && $kendaraan->status !== 'available') {
            return redirect()->back()->with('error', 
                "Gagal Approve! Kendaraan ini statusnya sedang {$kendaraan->status}. Harap selesaikan peminjaman sebelumnya atau ubah statusnya menjadi available terlebih dahulu.");
        }

        $peminjaman->update(['status' => 'approved']);

        if ($kendaraan) {
            $kendaraan->update(['status' => 'unavailable']);
        }

        if ($peminjaman->no_hp) {
            try {
                $this->sendWhatsAppNotification($peminjaman, 'approved', $request->note, $peminjaman->no_hp);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Approve: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Permintaan disetujui. Status kendaraan sekarang UNAVAILABLE.');
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
        $this->cekOtomatisKembali();

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