<?php

namespace App\Http\Controllers;

use App\Bidang;
use App\Item;
use App\ItemRequest;
use App\Transaction;
use App\RequestLinkZoom;
use App\LaporanRapat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        
        // --- BLOK KODE DUPLIKAT DIHAPUS DARI SINI ---
    }
    
    // --- ZOOM REQUEST METHODS ---

    public function createZoom()
    {
        $bidang = Bidang::orderBy('nama')->pluck('nama', 'id');
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

    public function approve(ItemRequest $reqBarang)
    {
        $request = $reqBarang; 
        $admin = Auth::user();

        if ($admin->role === 'admin_barang') {
            if (!$request->bidang_id || $admin->bidang_id !== $request->bidang_id) {
                abort(403, 'Anda tidak berhak menyetujui request dari bidang ini.');
            }
        }

        try {
            DB::transaction(function () use ($request) {
                $item = $request->item;
                if ($request->jumlah_request > $item->jumlah) {
                    throw new \Exception('Stok tidak mencukupi untuk menyetujui permintaan ini.');
                }
                $item->decrement('jumlah', $request->jumlah_request);
                $request->update(['status' => 'approved']);
                Transaction::create([
                    'request_id' => $request->id,
                    'item_id'    => $item->id,
                    'jumlah'     => $request->jumlah_request,
                    'tipe'       => 'keluar',
                    'tanggal'    => Carbon::now(),
                    'user_id'    => Auth::id(),
                ]);
            });

            if (!empty($request->no_hp)) {
                try {
                    $noHp = preg_replace('/[^0-9]/', '', trim($request->no_hp));
                    if (substr($noHp, 0, 1) === '0') { $noHp = '62' . substr($noHp, 1); }
                    elseif (substr($noHp, 0, 3) === '+62') { $noHp = substr($noHp, 1); }
                    elseif (substr($noHp, 0, 2) !== '62') { $noHp = '62' . $noHp; }
                    
                    setlocale(LC_TIME, 'id_ID.UTF-8');
                    $tanggal = $request->created_at->formatLocalized('%d %B %Y %H:%M');
                    $pesan = "[Request Barang Disetujui]\n\n"
                        . "Halo {$request->nama_pemohon},\n"
                        . "Request barang Anda telah *DISETUJUI*.\n\n"
                        . "Barang: {$request->item->nama_barang}\n"
                        . "Jumlah: {$request->jumlah_request}\n"
                        . "Tanggal: {$tanggal}\n\n"
                        . "Silakan ambil barang di bagian terkait. Terima kasih.";
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

    public function reject(ItemRequest $reqBarang, Request $request)
    {
        $admin = Auth::user();
        if ($admin->role === 'admin_barang') {
            if (!$reqBarang->bidang_id || $admin->bidang_id !== $reqBarang->bidang_id) {
                abort(403, 'Anda tidak berhak menolak request dari bidang ini.');
            }
        }
        $this->validate($request, ['note' => 'nullable|string|max:255',]);
        try {
            $reqBarang->update(['status' => 'rejected']);
            Transaction::create([
                'request_id' => $reqBarang->id,
                'item_id'    => $reqBarang->item_id,
                'jumlah'     => $reqBarang->jumlah_request,
                'tipe'       => 'rejected',
                'tanggal'    => Carbon::now(),
                'user_id'    => Auth::id(),
            ]);
            if (!empty($reqBarang->no_hp)) {
                try {
                    $noHp = preg_replace('/[^0-9]/', '', trim($reqBarang->no_hp));
                    if (substr($noHp, 0, 1) === '0') { $noHp = '62' . substr($noHp, 1); }
                    elseif (substr($noHp, 0, 3) === '+62') { $noHp = substr($noHp, 1); }
                    elseif (substr($noHp, 0, 2) !== '62') { $noHp = '62' . $noHp; }
                    
                    setlocale(LC_TIME, 'id_ID.UTF-8');
                    $tanggal = $reqBarang->created_at->formatLocalized('%d %B %Y %H:%M');
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

    public function dashboardDoc($id)
    {
        $catering = \App\Catering::findOrFail($id);
        
        return view('documents.dashboard_doc', compact('catering'));
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

    public function storeLaporanRapat(Request $request)
    {
        $request->validate([
            'pengunggah'  => 'required|string|max:255',
            'nip'         => 'nullable|string|max:50',
            'keterangan'  => 'nullable|string',
            'file'        => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'catering_id' => 'required|exists:catering,id', 
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            $fileOriginalName = null;
            $fileSize = 0;
            $mimeType = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileOriginalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                $fileName = time() . '_' . $fileOriginalName;
                
                $filePath = $file->storeAs('uploads/laporan_rapat', $fileName, 'public');
            }

            $laporan = new LaporanRapat();
            $laporan->catering_id        = $request->input('catering_id');
            $laporan->pengunggah         = $request->input('pengunggah');
            $laporan->nip                = $request->input('nip');
            $laporan->keterangan         = $request->input('keterangan');
            $laporan->file_laporan       = $filePath;
            $laporan->file_original_name = $fileOriginalName;
            $laporan->file_size          = $fileSize;
            $laporan->mime_type          = $mimeType;
            $laporan->status             = LaporanRapat::STATUS_SUBMITTED;
            $laporan->created_by         = null; // Atau Auth::id() jika login
            $laporan->save(); // <--- Eksekusi simpan

            // 4. Update Status Catering jadi Completed
            $catering = \App\Catering::find($request->input('catering_id'));
            if ($catering) {
                $catering->update([
                    'status' => \App\Catering::STATUS_COMPLETED
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Laporan berhasil diunggah dan status kegiatan selesai!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika gagal database
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }
}