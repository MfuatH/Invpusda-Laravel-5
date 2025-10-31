<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
        $items = Item::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Barang');

        // Header
        $headers = ['No', 'Kode Barang', 'Nama Barang', 'Satuan', 'Jumlah', 'Lokasi', 'Keterangan'];
        $sheet->fromArray([$headers], null, 'A1');

        // Styling header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Isi data
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

        // Auto size kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border seluruh tabel
        $sheet->getStyle('A1:G' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Simpan dan download
        $fileName = 'data_barang_' . date('Y-m-d_His') . '.xlsx';
        $tempFile = storage_path($fileName);
        (new Xlsx($spreadsheet))->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * EXPORT DATA TRANSAKSI KE EXCEL
     */
    public function exportTransaksi()
    {
        $user = Auth::user();
        $transactions = Transaction::with(['item', 'user.bidang', 'request.bidang'])
            ->orderBy('tanggal', 'desc');

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
        $headers = [
            'No', 'Tanggal', 'Nama Barang', 'Jumlah', 'Tipe',
            'Dicatat Oleh', 'Bidang Admin', 'Pemohon', 'Bidang Pemohon'
        ];
        $sheet->fromArray([$headers], null, 'A1');

        // Styling header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4BACC6']],
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

        // Auto size
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border seluruh tabel
        $sheet->getStyle('A1:I' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Rata tengah untuk kolom tertentu
        $sheet->getStyle('A1:A' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D1:D' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E1:E' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B' . ($row - 1))->getNumberFormat()->setFormatCode('dd-mm-yyyy hh:mm');

        // Simpan & kirim
        $fileName = 'riwayat_transaksi_' . date('Y-m-d_His') . '.xlsx';
        $tempFile = storage_path($fileName);
        (new Xlsx($spreadsheet))->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
