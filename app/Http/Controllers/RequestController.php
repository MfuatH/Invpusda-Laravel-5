<?php

namespace App\Http\Controllers;

use App\Bidang;
use App\Item;
use App\ItemRequest;
use App\Transaction;
use App\RequestLinkZoom; // Using the correct Zoom request model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class RequestController extends Controller
{
    public function landingPage()
    {
        // Assuming this view contains links to createBarang and createZoom
        return view('landing_page');
    }

    public function index()
    {
        $user = Auth::user();
        // This index typically shows ItemRequests, ZoomRequests may need a separate index
        $requestsQuery = ItemRequest::with(['item', 'bidang'])->latest();

        if ($user->role === 'admin_barang') {
            // Filter requests to the user's bidang via bidang_id
            if ($user->bidang_id) {
                $requestsQuery->where('bidang_id', $user->bidang_id);
            } else {
                // no bidang assigned -> no requests
                $requestsQuery->whereRaw('1 = 0');
            }
        }

        $requests = $requestsQuery->paginate(10);
        return view('admin_page.approvals.items', compact('requests'));
    }

    public function createBarang()
    {
        $items = Item::where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        $bidang = Bidang::orderBy('nama')->pluck('nama', 'id');
        return view('requests.barang_create', compact('items', 'bidang'));
    }

    public function storeBarang(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:25',
            'bidang_id' => 'required|exists:bidang,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah_request' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $reqItem) {
                    $item = Item::findOrFail($reqItem['item_id']);
                    if ($reqItem['jumlah_request'] > $item->jumlah) {
                        throw new Exception('Stok untuk barang "' . $item->nama_barang . '" tidak mencukupi.');
                    }
                    ItemRequest::create([
                        'nama_pemohon'   => $validated['nama_pemohon'],
                        'nip'            => $validated['nip'],
                        'no_hp'          => $validated['no_hp'],
                        'bidang_id'      => $validated['bidang_id'],
                        'item_id'        => $reqItem['item_id'],
                        'jumlah_request' => $reqItem['jumlah_request'],
                        'status'         => 'pending',
                    ]);
                }
                // Notifikasi ke admin barang sesuai bidang
                $admin = \App\User::where('role', 'admin_barang')
                    ->where('bidang_id', $validated['bidang_id'])
                    ->first();
                if ($admin && $admin->no_hp) {
                    $fontte = app(\App\Services\FontteService::class);
                    $msg = "[Permintaan Barang Baru]\nAda permintaan barang baru dari {$validated['nama_pemohon']} (Bidang ID: {$validated['bidang_id']}). Silakan cek aplikasi.";
                    $fontte->sendMessage($admin->no_hp, $msg);
                }
            });
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('landing-page')->with('success', 'Permintaan barang berhasil dikirim.');
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:25',
            'bidang_id' => 'required|exists:bidang,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah_request' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $reqItem) {
                    $item = Item::findOrFail($reqItem['item_id']);
                    if ($reqItem['jumlah_request'] > $item->jumlah) {
                        throw new Exception('Stok untuk barang "' . $item->nama_barang . '" tidak mencukupi.');
                    }
                    ItemRequest::create([
                        'nama_pemohon'   => $validated['nama_pemohon'],
                        'nip'            => $validated['nip'],
                        'no_hp'          => $validated['no_hp'],
                        'bidang_id'      => $validated['bidang_id'],
                        'item_id'        => $reqItem['item_id'],
                        'jumlah_request' => $reqItem['jumlah_request'],
                        'status'         => 'pending',
                    ]);
                }
                // Notifikasi ke admin barang sesuai bidang
                $admin = \App\User::where('role', 'admin_barang')
                    ->where('bidang_id', $validated['bidang_id'])
                    ->first();
                if ($admin && $admin->no_hp) {
                    $fontte = app(\App\Services\FontteService::class);
                    $msg = "[Permintaan Barang Baru]\nAda permintaan barang baru dari {$validated['nama_pemohon']} (Bidang ID: {$validated['bidang_id']}). Silakan cek aplikasi.";
                    $fontte->sendMessage($admin->no_hp, $msg);
                }
            });
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('landing-page')->with('success', 'Permintaan barang berhasil dikirim.');
    }
    
    // --- ZOOM REQUEST METHODS ---

    public function createZoom()
    {
        // Load the list of Bidang (Departments) for the form
        $bidang = Bidang::orderBy('nama')->pluck('nama', 'id');
        // Render the view for Zoom Request form
        return view('requests.zoom_create', compact('bidang')); 
    }

    public function storeZoom(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon'  => 'required|string|max:255',
            'nip'           => 'nullable|string|max:255',
            'no_hp'         => 'required|string|max:25',
            'bidang_id'     => 'required|exists:bidang,id',
            'nama_rapat'    => 'required|string|max:255',
            'jadwal_mulai'  => 'required|date',
            'jadwal_selesai'=> 'nullable|date|after_or_equal:jadwal_mulai',
            'keterangan'    => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $created = RequestLinkZoom::create([
                    'nama_pemohon'   => $validated['nama_pemohon'],
                    'nip'            => $validated['nip'],
                    'no_hp'          => $validated['no_hp'],
                    'bidang_id'      => $validated['bidang_id'],
                    'nama_rapat'     => $validated['nama_rapat'],
                    'jadwal_mulai'   => $validated['jadwal_mulai'],
                    'jadwal_selesai' => $validated['jadwal_selesai'] ?? null,
                    'keterangan'     => $validated['keterangan'],
                    'status'         => 'pending',
                ]);
                // Notifikasi ke admin barang sesuai bidang
                $admin = \App\User::where('role', 'admin_barang')
                    ->where('bidang_id', $validated['bidang_id'])
                    ->first();
                if ($admin && $admin->no_hp) {
                    $fontte = app(\App\Services\FontteService::class);
                    $msg = "[Permintaan Zoom Baru]\nAda permintaan link Zoom baru dari {$validated['nama_pemohon']} (Bidang ID: {$validated['bidang_id']}). Silakan cek aplikasi.";
                    $fontte->sendMessage($admin->no_hp, $msg);
                }
            });
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan request Zoom: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('landing-page')->with('success', 'Permintaan link Zoom berhasil dikirim. Silakan tunggu konfirmasi.');
    }
    
    // --- END ZOOM REQUEST METHODS ---

    // Ubah signature agar nama parameter cocok dengan route {reqBarang}
    public function approve(ItemRequest $reqBarang)
    {
        $request = $reqBarang; // alias
        $admin = Auth::user();

        // Cek otorisasi admin_barang
        if ($admin->role === 'admin_barang') {
            if (!$request->bidang_id || $admin->bidang_id !== $request->bidang_id) {
                abort(403, 'Anda tidak berhak menyetujui request dari bidang ini.');
            }
        }

        try {
            DB::transaction(function () use ($request) {
                $item = $request->item;

                // Cek stok barang
                if ($request->jumlah_request > $item->jumlah) {
                    throw new \Exception('Stok tidak mencukupi untuk menyetujui permintaan ini.');
                }

                // Kurangi stok dan ubah status
                $item->decrement('jumlah', $request->jumlah_request);
                $request->update(['status' => 'approved']);

                // Catat transaksi
                Transaction::create([
                    'request_id' => $request->id,
                    'item_id'    => $item->id,
                    'jumlah'     => $request->jumlah_request,
                    'tipe'       => 'keluar',
                    'tanggal'    => Carbon::now(),
                    'user_id'    => Auth::id(),
                ]);
            });

            // --- Kirim Notifikasi WA ---
            if (!empty($request->no_hp)) {
                try {
                    // Format nomor HP jadi standar internasional (62)
                    $noHp = preg_replace('/[^0-9]/', '', trim($request->no_hp));
                    if (substr($noHp, 0, 1) === '0') {
                        $noHp = '62' . substr($noHp, 1);
                    } elseif (substr($noHp, 0, 3) === '+62') {
                        $noHp = substr($noHp, 1);
                    } elseif (substr($noHp, 0, 2) !== '62') {
                        $noHp = '62' . $noHp;
                    }

                    // Format tanggal readable (tanpa isoFormat)
                    setlocale(LC_TIME, 'id_ID.UTF-8');
                    $tanggal = $request->created_at->formatLocalized('%d %B %Y %H:%M');

                    // Buat pesan WA
                    $pesan = "[Request Barang Disetujui]\n\n"
                        . "Halo {$request->nama_pemohon},\n"
                        . "Request barang Anda telah *DISETUJUI*.\n\n"
                        . "Barang: {$request->item->nama_barang}\n"
                        . "Jumlah: {$request->jumlah_request}\n"
                        . "Tanggal: {$tanggal}\n\n"
                        . "Silakan ambil barang di bagian terkait. Terima kasih.";

                    // Kirim via Fonnte
                    $wa = app(\App\Services\FontteService::class);
                    $wa->sendMessage($noHp, $pesan);
                } catch (\Exception $wae) {
                    // \Log::error('Gagal kirim WA approve: ' . $wae->getMessage());
                }
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')
            ->with('success', 'Permintaan berhasil disetujui dan notifikasi telah dikirim.');
    }

    // Ubah juga reject agar nama parameter cocok
    public function reject(ItemRequest $reqBarang, Request $request)
    {
        $admin = Auth::user();

        // Cek otorisasi admin_barang
        if ($admin->role === 'admin_barang') {
            if (!$reqBarang->bidang_id || $admin->bidang_id !== $reqBarang->bidang_id) {
                abort(403, 'Anda tidak berhak menolak request dari bidang ini.');
            }
        }

        // Validasi alasan opsional (boleh kosong)
        $this->validate($request, [
            'note' => 'nullable|string|max:255',
        ]);

        try {
            // Update status jadi rejected
            $reqBarang->update(['status' => 'rejected']);

            // Buat catatan transaksi (log)
            Transaction::create([
                'request_id' => $reqBarang->id,
                'item_id'    => $reqBarang->item_id,
                'jumlah'     => $reqBarang->jumlah_request,
                'tipe'       => 'rejected',
                'tanggal'    => Carbon::now(),
                'user_id'    => Auth::id(),
            ]);

            // Kirim notifikasi WhatsApp
            if (!empty($reqBarang->no_hp)) {
                try {
                    // Format nomor HP biar aman
                    $noHp = preg_replace('/[^0-9]/', '', trim($reqBarang->no_hp));
                    if (substr($noHp, 0, 1) === '0') {
                        $noHp = '62' . substr($noHp, 1);
                    } elseif (substr($noHp, 0, 3) === '+62') {
                        $noHp = substr($noHp, 1);
                    } elseif (substr($noHp, 0, 2) !== '62') {
                        $noHp = '62' . $noHp;
                    }

                    // Format tanggal lokal
                    setlocale(LC_TIME, 'id_ID.UTF-8');
                    $tanggal = $reqBarang->created_at->formatLocalized('%d %B %Y %H:%M');

                    // Buat pesan WA (termasuk alasan jika ada)
                    $pesan = "[Request Barang Ditolak]\n\n"
                        . "Halo {$reqBarang->nama_pemohon},\n"
                        . "Maaf, request barang Anda telah *DITOLAK*.\n\n"
                        . "Barang: {$reqBarang->item->nama_barang}\n"
                        . "Jumlah: {$reqBarang->jumlah_request}\n"
                        . "Tanggal: {$tanggal}";

                    if ($request->note) {
                        $pesan .= "\nAlasan: _{$request->note}_";
                    }

                    $pesan .= "\n\nSilakan hubungi admin jika ingin mengajukan ulang.";

                    // Kirim via Fonnte
                    $wa = app(\App\Services\FontteService::class);
                    $wa->sendMessage($noHp, $pesan);
                } catch (\Exception $wae) {
                    // \Log::error('Gagal kirim WA reject: ' . $wae->getMessage());
                }
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil ditolak dan notifikasi telah dikirim.');
    }
    
    public function createKonsumsi()
    {
        return view('requests.konsumsi_create');
    }

    public function createUndangan()
    {
        return view('documents.Undangan');
    }

    public function downloadPresensi()
    {
        return view('documents.download_presensi');
    }

    public function downloadNotulensi()
    {
        return view('documents.download_notulensi');
    }

    public function uploadNotulensinPresensi()
    {
        return view('documents.upload_notanpresensi');
    }
}
