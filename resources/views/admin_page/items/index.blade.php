@extends('layouts.app')

@section('title', 'Manajemen Barang')

@section('content')
<div class="container-fluid px-4">

    {{-- 💡 PERBAIKAN: SESSION ALERTS (Untuk menampilkan pesan sukses/gagal dari Controller) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> Gagal memproses permintaan. Silakan periksa formulir Anda.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {{-- Skrip untuk menampilkan kembali modal jika terjadi error validasi --}}
        <script> 
            $(document).ready(function() { 
                // Asumsi error hanya terjadi di modal addStock, jika ada error dari form lain, ini bisa menyebabkan masalah.
                $('#modalAddStock').modal('show'); 
            }); 
        </script>
    @endif

    {{-- Header --}}
    <div class="mb-3">
        <h4 class="font-weight-bold mb-3 d-flex align-items-center">
            <i class="fas fa-boxes text-primary mr-2"></i> Manajemen Barang
        </h4>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">

            {{-- Tombol dan Pencarian --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('barang.create') }}" class="btn btn-success btn-sm mr-2 mb-2">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Barang Baru
                    </a>
                    <a href="{{ route('export.barang') }}" class="btn btn-outline-success btn-sm mb-2">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                </div>

                {{-- Search --}}
                <form action="{{ route('barang.index') }}" method="GET" class="form-inline mb-2">
                    <input type="text" name="search" class="form-control form-control-sm mr-2"
                            placeholder="Cari barang..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </form>
            </div>

            {{-- Tabel Barang --}}
            @if(isset($items) && $items->count() > 0)
            <div class="table-responsive" style="min-width: 100%;">
                <table class="table table-bordered table-hover" style="width: 100%;">
                    <thead class="thead-dark">
                        <tr class="text-center align-middle">
                            <th style="width: 8%;">Kode</th>
                            <th style="width: 18%;">Nama Barang</th>
                            <th style="width: 8%;">Satuan</th>
                            <th style="width: 8%;">Jumlah</th>
                            <th style="width: 14%;">Lokasi</th>
                            <th style="width: 30%;">Keterangan</th>
                            <th style="width: 14%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td class="text-center text-muted">{{ $item->kode_barang }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-center">{{ $item->satuan }}</td>
                            <td class="text-center font-weight-bold">{{ $item->jumlah }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->keterangan, 80) }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center" style="gap: 10px;">

                                    {{-- Tombol Stok --}}
                                    <button type="button" 
                                            class="btn btn-link text-primary p-0 btn-sm btn-add-stock d-flex align-items-center"
                                            data-toggle="modal" {{-- 💡 PERBAIKAN: Tambahkan toggle modal --}}
                                            data-target="#modalAddStock" {{-- 💡 PERBAIKAN: Target ke ID Modal --}}
                                            data-id="{{ $item->id }}" 
                                            data-name="{{ $item->nama_barang }}" 
                                            data-amount="{{ $item->jumlah }}">
                                        <i class="fas fa-plus mr-1"></i> Stok
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('barang.edit', $item->id) }}" 
                                       class="btn btn-link text-warning p-0 btn-sm">
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('barang.destroy', $item->id) }}" 
                                            method="POST" 
                                            class="d-inline m-0 p-0">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }} {{-- Pastikan Anda menggunakan DELETE method spoofing --}}
                                        <button type="submit" 
                                                class="btn btn-link text-danger p-0 btn-sm" 
                                                onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                <small class="text-muted">
                    Menampilkan {{ $items->firstItem() }} - {{ $items->lastItem() }} dari {{ $items->total() }} barang
                </small>
                <div>
                    {{ $items->links() }}
                </div>
            </div>
            @else
            <p class="text-center text-muted mb-0">Belum ada data barang. 
                <a href="{{ route('barang.create') }}">Tambah sekarang</a>.
            </p>
            @endif
        </div>
    </div>
</div>

{{-- Modal Tambah Stok --}}
<div class="modal fade" id="modalAddStock" tabindex="-1" role="dialog" aria-labelledby="modalAddStockLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('barang.addStock') }}" method="POST">
                {{-- Gunakan metode ini untuk memastikan token ada dan tidak terganggu --}}
                <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
                
                <input type="hidden" name="item_id" id="modal_item_id">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Stok Barang</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p id="modal_item_name" class="font-weight-bold text-center"></p>

                    <div class="form-group">
                        <label>Stok Saat Ini</label>
                        <input type="text" id="current_amount" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jumlah yang Ditambahkan</label>
                        {{-- 💡 PERBAIKAN: Tambahkan value error lama jika ada --}}
                        <input type="number" name="add_amount" id="add_amount" class="form-control @if($errors->has('add_amount')) is-invalid @endif" 
                                min="1" required value="{{ old('add_amount') }}">
                        @if($errors->has('add_amount'))
                            <div class="invalid-feedback">{{ $errors->first('add_amount') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Catatan (Wajib Diisi untuk Log)</label>
                        {{-- 💡 PERBAIKAN: Catatan dibuat REQUIRED untuk log transaksi --}}
                        <input type="text" name="note" id="note" class="form-control @if($errors->has('note')) is-invalid @endif" 
                                required value="{{ old('note') }}">
                        @if($errors->has('note'))
                            <div class="invalid-feedback">{{ $errors->first('note') }}</div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tambah Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // 💡 PERBAIKAN: Menambahkan toggle modal di JS agar lebih rapi
    $('.btn-add-stock').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var amount = $(this).data('amount');

        $('#modal_item_id').val(id);
        $('#modal_item_name').text('Menambah Stok untuk: ' + name);
        $('#current_amount').val(amount);
        $('#add_amount').val(''); // Kosongkan input jumlah
        $('#note').val(''); // Kosongkan input catatan

        $('#modalAddStock').modal('show');
    });
    
    // 💡 PERBAIKAN: Jika ada error validasi saat submit, kita isi ulang data modal
    @if ($errors->any() && old('item_id'))
        $(document).ready(function() {
            var id = "{{ old('item_id') }}";
            var amount = $('.btn-add-stock[data-id="' + id + '"]').data('amount');
            var name = $('.btn-add-stock[data-id="' + id + '"]').data('name');

            $('#modal_item_id').val(id);
            $('#modal_item_name').text('Menambah Stok untuk: ' + name);
            $('#current_amount').val(amount);
            // Nilai add_amount dan note diisi otomatis oleh old() di form
            
            $('#modalAddStock').modal('show');
        });
    @endif
});
</script>
@endpush

@push('styles')
<style>
/* ... (Style CSS Anda) ... */
.table {
    font-size: 14px;
    background: #fff;
}
.table thead th {
    background-color: #1f2937; /* Tetap gelap */
    color: #fff;
    font-weight: 600;
    text-align: center;
}
/* ... (sisa style) ... */
.invalid-feedback {
    display: block; /* Memastikan feedback error muncul */
}
</style>
@endpush