<?php

namespace App\Http\Controllers;

use App\Item;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function index()
    {
        // Use pagination and consistent variable name expected by the view
        $items = Item::orderBy('nama_barang')->paginate(15);
        return view('admin_page.items.index', compact('items'));
    }

    public function create()
    {
        return view('admin_page.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string'
        ]);

        Item::create($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Item $barang)
    {
        return view('admin_page.items.show', compact('barang'));
    }

    public function edit(Item $barang)
    {
        return view('admin_page.items.edit', compact('barang'));
    }

    public function update(Request $request, Item $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string'
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Tambah stok barang (aksi dari halaman index)
     */
    public function addStock(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'add_amount' => 'required|integer|min:1',
            'note' => 'required|string|max:255', 
        ]);

        try {
            $item = Item::findOrFail($data['item_id']);

            $item->increment('jumlah', $data['add_amount']);

            Transaction::create([
                'item_id'    => $item->id,
                'jumlah'     => $data['add_amount'],
                'tipe'       => 'masuk', 
                'tanggal'    => Carbon::now(),
                'user_id'    => Auth::id(), 
                'keterangan' => $data['note'], 
            ]);

            return redirect()->route('barang.index')
                             ->with('success', "Stok untuk '{$item->nama_barang}' berhasil ditambah.");
                             
        } catch (\Exception $e) {
            return redirect()->route('barang.index')
                             ->withErrors(['error' => 'Gagal memproses stok: ' . $e->getMessage()])
                             ->withInput();
        }
    }
}
