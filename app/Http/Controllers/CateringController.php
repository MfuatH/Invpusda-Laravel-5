<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catering; // Panggil Model Catering
use App\User; // PENTING: Panggil Model User
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;   // PENTING: Tambahkan ini
use Illuminate\Support\Facades\Auth;  // PENTING: Tambahkan ini

class CateringController extends Controller
{
    /**
     * FUNGSI UNTUK GUEST (FORM BIASA)
     * Menyimpan data pemesanan catering baru dari form guest.
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan'      => 'required|string|max:100',
            'nip'               => 'nullable|string|max:50',
            'keperluan'         => 'required|string',
            'tanggal_kegiatan'  => 'required|date',
            'tempat'            => 'required|string|max:255',
            'jumlah_peserta'    => 'required|integer|min:1',
            'jenis_konsumsi'    => 'nullable|array',
            'nota_dinas_file'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan'        => 'nullable|string',
        ], [
            'nota_dinas_file.required' => 'File Nota Dinas wajib di-upload.',
        ]);

        DB::beginTransaction();
        $filePath = null;

        try {
            // Upload File
            $fileOriginalName = null;
            if ($request->hasFile('nota_dinas_file')) {
                $file = $request->file('nota_dinas_file');
                $fileOriginalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $fileOriginalName;
                $filePath = $file->storeAs('public/uploads/catering_nota_dinas', $fileName);
            }

            // Data untuk DB
            $data = [
                'nama_pemesan'      => $request->nama_pemesan,
                'nip'               => $request->nip,
                'keperluan'         => $request->keperluan,
                'tanggal_kegiatan'  => Carbon::parse($request->tanggal_kegiatan),
                'tempat'            => $request->tempat,
                'jumlah_peserta'    => $request->jumlah_peserta,
                'jenis_konsumsi'    => $request->jenis_konsumsi ? json_encode($request->jenis_konsumsi) : null,
                'keterangan'        => $request->keterangan,
                'nota_dinas_file'   => $filePath,
                'nota_dinas_original_name' => $fileOriginalName,
                'status'            => 'pending',
                'created_by'        => null,
            ];

            Catering::create($data);
            DB::commit();

            // KIRIM NOTIFIKASI WA
            try {
                $admin = User::where('role', 'super_admin')->first();
                
                if ($admin && $admin->no_hp) {
                    $pesanWA  = "*[Permintaan Catering Baru]*\n\n";
                    $pesanWA .= "Nama: {$data['nama_pemesan']}\n";
                    $pesanWA .= "Keperluan: {$data['keperluan']}\n";
                    $pesanWA .= "Tanggal: " . $data['tanggal_kegiatan']->format('d-m-Y') . "\n";
                    $pesanWA .= "Peserta: {$data['jumlah_peserta']}\n";
                    $pesanWA .= "Silakan cek aplikasi untuk Approve/Reject.";

                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://api.fonnte.com/send',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => [
                            'target' => $admin->no_hp,
                            'message' => $pesanWA,
                            'countryCode' => '62',
                        ],
                        CURLOPT_HTTPHEADER => [
                            'Authorization: ' . env('FONTTE_API_KEY')
                        ],
                    ]);

                    curl_exec($curl);
                    curl_close($curl);
                }
            } catch (\Exception $waError) {
                Log::error('WA Error Catering: ' . $waError->getMessage());
            }

            session()->flash('success', 'Permintaan catering berhasil dikirim!');
            return redirect()->route('documents.template_doc');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($filePath && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Halaman Template Dokumen
     */
    public function templateDoc()
    {
        return view('documents.template_doc');
    }

    /**
     * Jika user tidak login setelah submit
     */
    public function successPage()
    {
        if (!session()->has('success')) {
            return redirect()->route('request.konsumsi.create');
        }
        return redirect()->route('documents.template_doc');
    }



    /*
    |--------------------------------------------------------------------------
    | FUNGSI UNTUK HALAMAN ADMIN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $caterings = Catering::latest()->with('creator')->paginate(10);
        return view('admin_page.approvals.catering', compact('caterings'));
    }

    public function show($id)
    {
        $catering = Catering::findOrFail($id);
        return view('admin_page.approvals.catering_show', compact('catering')); // Asumsi
    }

    public function edit($id)
    {
        $catering = Catering::findOrFail($id);
        return view('admin_page.approvals.catering_edit', compact('catering')); // Asumsi
    }

    public function update(Request $request, $id)
    {
        // ... (Logika jika admin meng-edit data via form) ...
        return redirect()->route('catering.index')->with('success', 'Data catering berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // ... (Logika hapus data) ...
        return redirect()->route('catering.index')->with('success', 'Data catering berhasil dihapus.');
    }


    // ===================================================================
    // --- FUNGSI BARU UNTUK MODAL APPROVE / REJECT ---
    // ===================================================================

    /**
     * Menyetujui permintaan catering (dipanggil dari modal).
     */
    public function approve(Request $request, $id)
    {
        $catering = Catering::findOrFail($id);
        
        $catering->update([
            'status' => 'approved',
            'admin_note' => $request->input('note'),
            'approved_by' => Auth::id(), // Mengambil ID admin yang login
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        // ... (Tambahkan logika notif WA ke pemesan di sini jika perlu) ...

        // ==========================================================
        // --- PERBAIKAN REDIRECT DI SINI ---
        // ==========================================================
        // Kembali ke halaman approval catering
        return redirect()->route('catering.index')->with('success', 'Pemesanan catering telah disetujui.');
    }

    /**
     * Menolak permintaan catering (dipanggil dari modal).
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['note' => 'required|string']); 
        
        $catering = Catering::findOrFail($id);
        
        $catering->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('note'),
            'approved_by' => Auth::id(), // Mengambil ID admin yang login
            'approved_at' => null,
        ]);

        // ... (Tambahkan logika notif WA ke pemesan di sini jika perlu) ...

        // ==========================================================
        // --- PERBAIKAN REDIRECT DI SINI ---
        // ==========================================================
        // Kembali ke halaman approval catering
        return redirect()->route('catering.index')->with('success', 'Pemesanan catering telah ditolak.');
    }
}