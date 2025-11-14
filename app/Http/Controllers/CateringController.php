<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catering; // Panggil Model Catering
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CateringController extends Controller
{
    /**
     * FUNGSI UNTUK GUEST (FORM BIASA)
     * Menyimpan data pemesanan catering baru dari form guest.
     */
    public function store(Request $request)
    {
        // Validasi minimal (opsional, tapi disarankan)
        // Jika benar-benar tidak mau validasi, hapus bagian ini
        $request->validate([
            'nama_pemesan' => 'required|string|max:100',
            'keperluan' => 'required|string',
            'tanggal_kegiatan' => 'required|date',
            'jumlah_peserta' => 'required|integer',
            'nota_dinas_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            // --- 1. Proses File (Nota Dinas) ---
            $fileOriginalName = null;
            if ($request->hasFile('nota_dinas_file')) {
                $file = $request->file('nota_dinas_file');
                $fileOriginalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $fileOriginalName;
                $filePath = $file->storeAs('uploads/catering_nota_dinas', $fileName);
            }

            // --- 2. Siapkan Data ---
            $data = [
                'nama_pemesan'     => $request->input('nama_pemesan'),
                'nip'              => $request->input('nip'),
                'keperluan'        => $request->input('keperluan'),
                'tanggal_kegiatan' => $request->input('tanggal_kegiatan'),
                'tempat'           => $request->input('tempat'),
                'jumlah_peserta'   => $request->input('jumlah_peserta'),
                
                // Jika jenis_konsumsi adalah array dari checkbox
                'jenis_konsumsi'   => $request->input('jenis_konsumsi'), // Model sudah handle json_encode
                
                'keterangan'       => $request->input('keterangan'),
                
                // Info File
                'nota_dinas_file'  => $filePath,
                'nota_dinas_original_name' => $fileOriginalName,
                
                // Status Default untuk GUEST
                'status'           => 'pending', // Sesuai Opsi 1 (string langsung)
                'created_by'       => null,      // Karena GUEST
            ];

            // --- 3. Simpan ke Database ---
            Catering::create($data);

            DB::commit();

            // Kembalikan ke halaman form dengan pesan sukses
            return redirect()->back()->with('success', 'Permintaan catering berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika database gagal
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Kembalikan ke halaman form dengan pesan error
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FUNGSI UNTUK HALAMAN ADMIN (CRUD LENGKAP)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar semua catering (Halaman Admin).
     */
    public function index()
    {
        $caterings = Catering::latest()->with('creator')->paginate(10);
        
        // Ganti 'admin_page.catering.index' sesuai path view admin Anda
        return view('admin_page.approvals.catering', compact('caterings'));
    }

    /**
     * Menampilkan detail satu catering (Halaman Admin).
     */
    public function show($id)
    {
        $catering = Catering::with('laporanRapat', 'approver')->findOrFail($id);
        
        // Ganti 'admin_page.catering.show' sesuai path view admin Anda
        return view('admin_page.approvals.catering', compact('catering'));
    }

    /**
     * Menampilkan form untuk mengedit catering (Halaman Admin).
     * (Termasuk form untuk Approve/Reject)
     */
    public function edit($id)
    {
        $catering = Catering::findOrFail($id);
        
        // Ganti 'admin_page.catering.edit' sesuai path view admin Anda
        return view('admin_page.approvals.catering', compact('catering'));
    }

    /**
     * Menyimpan perubahan data catering (Halaman Admin).
     */
    public function update(Request $request, $id)
    {
        $catering = Catering::findOrFail($id);

        $data = $request->except(['_token', '_method', 'nota_dinas_file']);

        // Jika Admin mengganti file nota dinas
        if ($request->hasFile('nota_dinas_file')) {
            $file = $request->file('nota_dinas_file');
            $fileOriginalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $fileOriginalName;
            
            // Hapus file lama
            if ($catering->nota_dinas_file && Storage::exists($catering->nota_dinas_file)) {
                Storage::delete($catering->nota_dinas_file);
            }

            // Simpan file baru
            $data['nota_dinas_file'] = $file->storeAs('uploads/catering_nota_dinas', $fileName);
            $data['nota_dinas_original_name'] = $fileOriginalName;
        }

        // Jika ini adalah aksi approval dari admin
        if ($request->has('status') && $request->input('status') == 'approved') {
            // $data['approved_by'] = Auth::id(); // Ambil ID admin yang login
            $data['approved_at'] = now();
            $data['rejection_reason'] = null;
        }

        // Jika ini adalah aksi rejection
        if ($request->has('status') && $request->input('status') == 'rejected') {
            // $data['approved_by'] = Auth::id();
            $data['approved_at'] = null;
            // 'rejection_reason' harusnya diisi dari form
        }

        $catering->update($data);

        return redirect()->route('approvals.catering')->with('success', 'Data catering berhasil diperbarui.');
    }

    /**
     * Menghapus data catering (Halaman Admin).
     */
    public function destroy($id)
    {
        $catering = Catering::findOrFail($id);
        $filePath = $catering->nota_dinas_file;

        DB::beginTransaction();
        try {
            // Hapus relasi (Laporan Rapat) jika ada (opsional, tergantung kebutuhan)
            // $catering->laporanRapat()->delete(); 

            // Hapus data catering
            $catering->delete();

            // Hapus file dari storage
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            DB::commit();
            return redirect()->route('approvals.catering')->with('success', 'Data catering berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('approvals.catering')->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}