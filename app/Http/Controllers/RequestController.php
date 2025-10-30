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
        $request = $reqBarang; // opsional: buat alias agar kode lama tetap bekerja
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
                    throw new Exception('Stok tidak mencukupi untuk menyetujui permintaan ini.');
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

            if ($request->no_hp) {
                try {
                    $wa = app(\App\Services\WhatsAppService::class);
                    $message = "[Request Barang Disetujui]\nRequest barang Anda telah disetujui.\nBarang: {$request->item->nama_barang}\nJumlah: {$request->jumlah_request}\nTanggal: " . $request->created_at->format('d-m-Y H:i');
                    $wa->sendMessage($request->no_hp, $message);
                } catch (Exception $wae) {
                    // optional log
                }
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil disetujui.');
    }

    // Ubah juga reject agar nama parameter cocok
    public function reject(ItemRequest $reqBarang)
    {
        $request = $reqBarang; // alias
        $admin = Auth::user();

        if ($admin->role === 'admin_barang') {
            if (!$request->bidang_id || $admin->bidang_id !== $request->bidang_id) {
                abort(403, 'Anda tidak berhak menolak request dari bidang ini.');
            }
        }

        try {
            $request->update(['status' => 'rejected']);
            
            if ($request->no_hp) {
                try {
                    $wa = app(\App\Services\WhatsAppService::class);
                    $message = "[Request Barang Ditolak]\nMaaf, request barang Anda ditolak.\nBarang: {$request->item->nama_barang}\nJumlah: {$request->jumlah_request}\nTanggal: " . $request->created_at->format('d-m-Y H:i');
                    $wa->sendMessage($request->no_hp, $message);
                } catch (Exception $wae) {
                    // optional log
                }
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil ditolak.');
    }
}
