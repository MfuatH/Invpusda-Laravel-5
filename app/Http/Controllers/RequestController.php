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
            $requestsQuery->whereHas('bidang', function ($query) use ($user) {
                $query->where('nama', $user->bidang);
            });
        }

        $requests = $requestsQuery->paginate(10);
        return view('requests.index', compact('requests'));
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
            // --- BARIS TAMBAHAN INI ---
            'nama_rapat'    => 'required|string|max:255', // Validasi field baru
            // --- END BARIS TAMBAHAN ---
            'jadwal_mulai'  => 'required|date',
            'jadwal_selesai'=> 'nullable|date|after_or_equal:jadwal_mulai',
            'keterangan'    => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                RequestLinkZoom::create([ 
                    'nama_pemohon'   => $validated['nama_pemohon'],
                    'nip'            => $validated['nip'],
                    'no_hp'          => $validated['no_hp'],
                    'bidang_id'      => $validated['bidang_id'],
                    // --- BARIS TAMBAHAN INI ---
                    'nama_rapat'     => $validated['nama_rapat'], // Simpan field baru
                    // --- END BARIS TAMBAHAN ---
                    'jadwal_mulai'   => $validated['jadwal_mulai'],
                    'jadwal_selesai' => $validated['jadwal_selesai'] ?? null,
                    'keterangan'     => $validated['keterangan'],
                    'status'         => 'pending',
                ]);
            });
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan request Zoom: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('landing-page')->with('success', 'Permintaan link Zoom berhasil dikirim. Silakan tunggu konfirmasi.');
    }
    
    // --- END ZOOM REQUEST METHODS ---

    public function approve(ItemRequest $request)
    {
        $admin = Auth::user();

        if ($admin->role === 'admin_barang') {
            if (!$request->bidang || $admin->bidang !== $request->bidang->nama) {
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
                    // Pastikan service 'WhatsAppService' ada di 'app/Services/'
                    $wa = app(\App\Services\WhatsAppService::class);
                    $message = "[Request Barang Disetujui]\nRequest barang Anda telah disetujui.\nBarang: {$request->item->nama_barang}\nJumlah: {$request->jumlah_request}\nTanggal: " . $request->created_at->format('d-m-Y H:i');
                    $wa->sendMessage($request->no_hp, $message);
                } catch (Exception $wae) {
                    // Log::error('Gagal kirim WA notif approve: ' . $wae->getMessage()); // Opsional
                }
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil disetujui.');
    }

    public function reject(ItemRequest $request)
    {
        $admin = Auth::user();

        if ($admin->role === 'admin_barang') {
            if (!$request->bidang || $admin->bidang !== $request->bidang->nama) {
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
                    // Log::error('Gagal kirim WA notif reject: ' . $wae->getMessage()); // Opsional
                }
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil ditolak.');
    }
}
