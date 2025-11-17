<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaporanRapat; // Import Model
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Untuk Auth Admin
use Carbon\Carbon; // Untuk tanggal

class LaporanRapatController extends Controller
{
    /**
     * Menampilkan daftar dokumen (Admin Page).
     */
    public function index(Request $request)
    {
        $query = LaporanRapat::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pengunggah', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('file_original_name', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }
        
        $documents = $query->with('catering')->latest()->paginate(10);
        return view('admin_page.documents.index', compact('documents'));
    }

    /**
     * Menyimpan dokumen baru (dari Modal Upload Admin, jika ada)
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengunggah' => 'required|string|max:255',
            'nip'        => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'file'       => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240'
        ]);

        $filePath = null;
        $fileOriginalName = null;

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileOriginalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $fileOriginalName;
                // Simpan ke disk 'public'
                $filePath = $file->storeAs('uploads/laporan_rapat', $fileName, 'public'); 
            }

            LaporanRapat::create([
                'pengunggah'         => $request->input('pengunggah'),
                'nip'                => $request->input('nip'),
                'keterangan'         => $request->input('keterangan'),
                'file_laporan'       => $filePath,
                'file_original_name' => $fileOriginalName,
                'file_size'          => $file->getSize(),
                'mime_type'          => $file->getMimeType(),
                'status'             => LaporanRapat::STATUS_VERIFIED, 
                'created_by'         => Auth::id(),
                'verified_by'        => Auth::id(),
                'verified_at'        => now(),
            ]);

            return redirect()->route('documents.index')->with('success', 'Dokumen berhasil di-upload.');

        } catch (\Exception $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update data dokumen (dari Modal Edit).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pengunggah' => 'required|string|max:255',
            'nip'        => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'file'       => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240'
        ]);

        $dokumen = LaporanRapat::findOrFail($id);
        $data = $request->only(['pengunggah', 'nip', 'keterangan']);

        if ($request->hasFile('file')) {
            $oldPath = $dokumen->file_laporan;
            // Hapus file lama (Cek di kedua disk)
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            } elseif ($oldPath && Storage::disk('local')->exists($oldPath)) {
                Storage::disk('local')->delete($oldPath);
            }

            $file = $request->file('file');
            $fileOriginalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $fileOriginalName;
            
            // Simpan file baru ke 'public'
            $data['file_laporan'] = $file->storeAs('uploads/laporan_rapat', $fileName, 'public');
            $data['file_original_name'] = $fileOriginalName;
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $dokumen->update($data);
        return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui.');
    }

    /**
     * Verifikasi Laporan (Update Status).
     */
    public function verify(Request $request, $id)
    {
        $laporan = LaporanRapat::findOrFail($id);
        $laporan->update([
            'status' => LaporanRapat::STATUS_VERIFIED,
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Dokumen berhasil diverifikasi.');
    }

    /**
     * ==========================================================
     * --- PERBAIKAN PENTING: FUNGSI DOWNLOAD (CEK 2 LOKASI) ---
     * ==========================================================
     */
    public function download($id)
    {
        $laporan = LaporanRapat::findOrFail($id);
        $path = $laporan->file_laporan;

        // Bersihkan path jika masih ada 'public/' (kesalahan lama)
        if (strpos($path, 'public/') === 0) { $path = substr($path, 7); }

        // Cek 1: Disk 'public' (untuk file baru)
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, $laporan->file_original_name);
        }

        // Cek 2: Disk 'local' (untuk file lama)
        if ($path && Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path, $laporan->file_original_name);
        }

        return redirect()->back()->with('error', 'File fisik tidak ditemukan di server.');
    }

    /**
     * ==========================================================
     * --- PERBAIKAN PENTING: FUNGSI HAPUS (CEK 2 LOKASI) ---
     * ==========================================================
     */
    public function destroy($id)
    {
        $laporan = LaporanRapat::findOrFail($id);
        $filePath = $laporan->file_laporan;

        // Bersihkan path jika masih ada 'public/' (kesalahan lama)
        if (strpos($filePath, 'public/') === 0) { $filePath = substr($filePath, 7); }

        DB::beginTransaction();
        try {
            $laporan->delete();

            // Coba hapus dari disk 'public'
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            } 
            // Coba hapus juga dari disk 'local'
            elseif ($filePath && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * ==========================================================
     * --- PERBAIKAN PENTING: FUNGSI "LIHAT" (CEK 2 LOKASI) ---
     * ==========================================================
     */
    public function preview($id)
    {
        $laporan = LaporanRapat::findOrFail($id);
        $path = $laporan->file_laporan;

        // Bersihkan path jika masih ada 'public/' (kesalahan lama)
        if (strpos($path, 'public/') === 0) { $path = substr($path, 7); }

        // Cek 1: Disk 'public' (untuk file baru)
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        // Cek 2: Disk 'local' (untuk file lama)
        if ($path && Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->response($path);
        }

        abort(404, 'File tidak ditemukan.');
    }
}