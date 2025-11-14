<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catering; // Panggil Model Catering
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CateringController extends Controller
{
    /**
     * FUNGSI UNTUK GUEST (FORM BIASA)
     * Menyimpan data pemesanan catering baru dari form guest.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_pemesan' => 'required|string|max:100',
            'keperluan' => 'required|string',
            'tanggal_kegiatan' => 'required',
            'jumlah_peserta' => 'required|integer',
            'nota_dinas_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            // 2. Proses File
            $fileOriginalName = null;
            if ($request->hasFile('nota_dinas_file')) {
                $file = $request->file('nota_dinas_file');
                $fileOriginalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $fileOriginalName;
                $filePath = $file->storeAs('uploads/catering_nota_dinas', $fileName);
            }

            // 3. Siapkan Data
            $data = [
                'nama_pemesan'     => $request->input('nama_pemesan'),
                'nip'              => $request->input('nip'),
                'keperluan'        => $request->input('keperluan'),
                'tanggal_kegiatan' => \Carbon\Carbon::parse($request->input('tanggal_kegiatan')), 
                'tempat'           => $request->input('tempat'),
                'jumlah_peserta'   => $request->input('jumlah_peserta'),
                'jenis_konsumsi'   => $request->input('jenis_konsumsi') ? json_encode($request->input('jenis_konsumsi')) : null, 
                'keterangan'       => $request->input('keterangan'),
                'nota_dinas_file'  => $filePath,
                'nota_dinas_original_name' => $fileOriginalName,
                'status'           => 'pending',
                'created_by'       => null,
            ];

            // 4. Simpan Database
            Catering::create($data);
            DB::commit();

            // ==========================================================
            // --- 5. NOTIFIKASI WA (VERSI PHP NATIVE CURL - PASTI JALAN) ---
            // ==========================================================
            try {
                $admin = \App\User::where('role', 'super_admin')->first();
                
                if ($admin && $admin->no_hp) {
                    $pesanWA  = "*[Permintaan Catering Baru]*\n\n";
                    $pesanWA .= "Halo Super Admin, ada pengajuan baru:\n";
                    $pesanWA .= "👤 Nama: " . $data['nama_pemesan'] . "\n";
                    $pesanWA .= "📝 Keperluan: " . $data['keperluan'] . "\n";
                    $pesanWA .= "📅 Tgl: " . $data['tanggal_kegiatan']->format('d-m-Y H:i') . "\n"; 
                    $pesanWA .= "👥 Peserta: " . $data['jumlah_peserta'] . "\n";
                    $pesanWA .= "Silakan cek aplikasi untuk Approve/Reject.";

                    // --- MENGGUNAKAN CURL PHP NATIVE ---
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $admin->no_hp,
                        'message' => $pesanWA,
                        'countryCode' => '62',
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . env('FONTTE_API_KEY') 
                    ),
                    ));

                    $response = curl_exec($curl);
                    if ($response === false) {
                        \Log::error('Curl Error: ' . curl_error($curl));
                    }
                    curl_close($curl);
                    // -------------------------------------
                }
            } catch (\Exception $waError) {
                \Log::error('Gagal kirim WA Catering: ' . $waError->getMessage());
            }
            // ==========================================================

            return redirect()->route('catering.success')
                ->with('success', 'Permintaan catering berhasil dikirim! Silakan unduh template dokumen.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function successPage()
    {
        // 1. KEAMANAN: Cek apakah ada session 'success'
        // Jika user mengetik URL manual tanpa submit form, session ini tidak ada.
        if (!session()->has('success')) {
            // Tendang balik ke form awal
            return redirect()->route('requests.konsumsi_create'); 
        }

        // 2. Jika punya tiket, tampilkan view template_doc
        // Kita re-flash session 'success' agar bisa ditampilkan di view (opsional)
        session()->reflash(); 
        
        return view('documents.template_doc');
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