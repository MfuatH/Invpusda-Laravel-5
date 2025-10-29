@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
<div class="container-fluid">
	<div class="d-flex align-items-center justify-content-between mb-4">
		<h1 class="h4 mb-0">Manajemen Barang</h1>
		<div>
			<a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary">
				<i class="fas fa-plus mr-1"></i> Tambah Barang Baru
			</a>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
			@if(isset($items) && $items->count() > 0)
			<div class="table-responsive">
				<table class="table table-hover table-sm">
					<thead>
						<tr>
							<th style="width:40px">#</th>
							<th>Kode</th>
							<th>Nama Barang</th>
							<th>Jumlah</th>
							<th>Satuan</th>
							<th>Lokasi</th>
							<th>Keterangan</th>
							<th style="width:200px">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach($items as $idx => $item)
						<tr>
							<td>{{ $items->firstItem() + $idx }}</td>
							<td>{{ $item->kode_barang }}</td>
							<td>{{ $item->nama_barang }}</td>
							<td>{{ $item->jumlah }}</td>
							<td>{{ $item->satuan }}</td>
							<td>{{ $item->lokasi }}</td>
							<td>{{ \Illuminate\Support\Str::limit($item->keterangan, 60) }}</td>
							<td>
								<a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary">
									<i class="fas fa-edit"></i> Edit
								</a>

								<button type="button" class="btn btn-sm btn-success btn-add-stock" 
										data-id="{{ $item->id }}" data-name="{{ $item->nama_barang }}" data-amount="{{ $item->jumlah }}">
									<i class="fas fa-plus"></i> Tambah Stok
								</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="d-flex justify-content-between align-items-center mt-3">
				<div>
					Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} barang
				</div>
				<div>
					{{ $items->links() }}
				</div>
			</div>
			@else
			<p class="text-center text-muted mb-0">Belum ada barang terdaftar. <a href="{{ route('barang.create') }}">Buat sekarang</a>.</p>
			@endif
		</div>
	</div>

</div>

<!-- Modal Tambah Stok -->
<div class="modal fade" id="modalAddStock" tabindex="-1" role="dialog" aria-labelledby="modalAddStockLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="{{ route('barang.addStock') }}" method="POST">
				{{ csrf_field() }}
				<input type="hidden" name="item_id" id="modal_item_id">
				<div class="modal-header">
					<h5 class="modal-title" id="modalAddStockLabel">Tambah Stok</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p id="modal_item_name" class="font-weight-bold"></p>

					<div class="form-group">
						<label for="current_amount">Stok Saat Ini</label>
						<input type="text" id="current_amount" class="form-control" readonly>
					</div>

					<div class="form-group">
						<label for="add_amount">Jumlah yang Ditambahkan</label>
						<input type="number" name="add_amount" id="add_amount" class="form-control" min="1" required>
					</div>

					<div class="form-group">
						<label for="note">Catatan (opsional)</label>
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
	$(function(){
		// open modal and populate fields
		$('.btn-add-stock').on('click', function(){
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

