<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** 
     * EXPORT DATA BARANG KE EXCEL 
     */
    public function exportBarang()
    {
        $user = Auth::user();
        $items = Item::query();

        // if ($user->role === 'admin_barang' && $user->bidang_id) {
        //     $items->where('bidang_id', $user->bidang_id);
        // }

        $items = $items->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Barang');

        // Header
        $sheet->fromArray([
            ['No', 'Kode Barang', 'Nama Barang', 'Satuan', 'Jumlah', 'Lokasi', 'Keterangan']
        ]);

        $row = 2;
        foreach ($items as $index => $item) {
            $sheet->fromArray([
                [
                    $index + 1,
                    $item->kode_barang,
                    $item->nama_barang,
                    $item->satuan,
                    $item->jumlah,
                    $item->lokasi,
                    $item->keterangan
                ]
            ], null, 'A' . $row);
            $row++;
        }

        $fileName = 'data_barang_' . date('Y-m-d_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Simpan sementara di storage/temp
        $tempFile = storage_path($fileName);
        $writer->save($tempFile);

        // Kirim ke browser (Laravel 5.5)
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /** 
     * EXPORT DATA TRANSAKSI KE EXCEL 
     */
    public function exportTransaksi()
    {
        $user = Auth::user();
        $transactions = Transaction::with(['item', 'user', 'request'])->orderBy('tanggal', 'desc');

        if ($user->role === 'admin_barang' && $user->bidang_id) {
            $transactions->whereHas('user', function ($q) use ($user) {
                $q->where('bidang_id', $user->bidang_id);
            });
        }

        $transactions = $transactions->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Transaksi');

        // Header
        $sheet->fromArray([
            ['No', 'Tanggal', 'Nama Barang', 'Jumlah', 'Tipe', 'Dicatat Oleh', 'Bidang Admin', 'Pemohon', 'Bidang Pemohon']
        ]);

        $row = 2;
        foreach ($transactions as $index => $t) {
            $sheet->fromArray([
                [
                    $index + 1,
                    Carbon::parse($t->tanggal)->format('d-m-Y H:i'),
                    $t->item->nama_barang ?? 'Barang Dihapus',
                    $t->jumlah,
                    ucfirst($t->tipe),
                    $t->user->name ?? '-',
                    $t->user->bidang->nama ?? '-',
                    $t->request->nama_pemohon ?? '-',
                    $t->request->bidang->nama ?? '-'
                ]
            ], null, 'A' . $row);
            $row++;
        }

        $fileName = 'riwayat_transaksi_' . date('Y-m-d_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Simpan sementara
        $tempFile = storage_path($fileName);
        $writer->save($tempFile);

        // Download dan hapus file setelah dikirim
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
