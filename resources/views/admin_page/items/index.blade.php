@extends('layouts.app')

@section('title', 'Manajemen Barang')

@section('content')
<div class="container-fluid px-4">

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
                    <a href="#" class="btn btn-outline-success btn-sm mb-2">
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
                @csrf
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
                        <input type="number" name="add_amount" id="add_amount" class="form-control" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <input type="text" name="note" id="note" class="form-control">
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
    $('.btn-add-stock').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var amount = $(this).data('amount');

        $('#modal_item_id').val(id);
        $('#modal_item_name').text(name);
        $('#current_amount').val(amount);
        $('#add_amount').val('');

        $('#modalAddStock').modal('show');
    });
});
</script>
@endpush
