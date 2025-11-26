@extends('layouts.app')

@section('title', 'Daftar Kendaraan Dinas')

@section('content')
<div class="container-fluid px-4">

    {{-- Header Judul & Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-weight-bold mb-0 d-flex align-items-center">
            <i class="fas fa-car-side text-primary mr-2"></i> Daftar Kendaraan Dinas
        </h4>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus mr-1"></i> Tambah Kendaraan
        </button>
    </div>


    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Card Tabel --}}
    <div class="card shadow-sm w-100">
        <div class="card-body">
            @if ($kendaraans->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-car fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted mb-0">Belum ada data kendaraan. Silakan tambahkan.</p>
                </div>
            @else
                <div class="table-responsive">
                    {{-- PERUBAHAN: Ditambahkan 'table-bordered' --}}
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th>JENIS KENDARAAN / MERK</th>
                                <th>PLAT NOMOR</th>
                                <th>STATUS</th>
                                <th style="width: 150px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kendaraans as $index => $k)
                            <tr>
                                <td>{{ $loop->iteration + $kendaraans->firstItem() - 1 }}</td>
                                <td class="text-left font-weight">{{ $k->jenis }}</td>
                                <td>
                                    <span class="badge badge-light border px-3 py-2" style="font-size: 0.9rem; letter-spacing: 1px;">
                                        {{ $k->plat_no ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($k->status == 'available')
                                        <span class="badge badge-success">Available</span>
                                    @elseif($k->status == 'unavailable')
                                        <span class="badge badge-warning text-dark">Unavailable</span>
                                    @elseif($k->status == 'maintenance')
                                        <span class="badge badge-danger">Maintenance</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $k->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{-- Tombol Edit --}}
                                        <button class="btn btn-sm btn-info mr-2 edit-btn"
                                            data-toggle="modal" data-target="#editModal"
                                            data-id="{{ $k->id }}"
                                            data-jenis="{{ $k->jenis }}" 
                                            data-plat="{{ $k->plat_no }}"
                                            data-status="{{ $k->status }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        {{-- Tombol Hapus --}}
                                        <button class="btn btn-sm btn-danger delete-btn"
                                            data-toggle="modal" data-target="#deleteModal"
                                            data-id="{{ $k->id }}"
                                            data-jenis="{{ $k->jenis }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $kendaraans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL TAMBAH KENDARAAN --}}
{{-- =================================================================== --}}
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('kendaraan.store_unit') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Tambah Kendaraan Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Jenis Kendaraan / Merk <span class="text-danger">*</span></label>
                        <input type="text" name="jenis" class="form-control" placeholder="Contoh: Toyota Innova Reborn" required>
                    </div>
                    <div class="form-group">
                        <label>Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_no" class="form-control" placeholder="Contoh: L 1234 AB" required>
                    </div>
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="available">Available (Tersedia)</option>
                            <option value="unavailable">Unavailable (Tidak Tersedia)</option>
                            <option value="maintenance">Maintenance (Perbaikan)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL EDIT KENDARAAN --}}
{{-- =================================================================== --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editForm" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-edit mr-1"></i> Edit Data Kendaraan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Jenis Kendaraan / Merk <span class="text-danger">*</span></label>
                        <input type="text" name="jenis" id="edit-jenis" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" name="plat_no" id="edit-plat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit-status" class="form-control">
                            <option value="available">Available (Tersedia)</option>
                            <option value="unavailable">Unavailable (Tidak Tersedia)</option>
                            <option value="maintenance">Maintenance (Perbaikan)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================================================================== --}}
{{-- MODAL HAPUS KENDARAAN --}}
{{-- =================================================================== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash mr-1"></i> Hapus Kendaraan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kendaraan ini?</p>
                    <div class="alert alert-warning font-weight-bold text-center text-dark" id="delete-jenis"></div>
                    <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
    // Template Route
    const updateRoute = '{{ route("kendaraan.update", ":id") }}';
    const deleteRoute = '{{ route("kendaraan.destroy", ":id") }}';

    // Handle Edit Button
    $('.edit-btn').on('click', function(){
        let id = $(this).data('id');
        let jenis = $(this).data('jenis');
        let plat = $(this).data('plat');
        let status = $(this).data('status');

        // Set Form Action
        $('#editForm').attr('action', updateRoute.replace(':id', id));

        // Fill Input
        $('#edit-jenis').val(jenis);
        $('#edit-plat').val(plat);
        $('#edit-status').val(status);
    });

    // Handle Delete Button
    $('.delete-btn').on('click', function(){
        let id = $(this).data('id');
        let jenis = $(this).data('jenis');

        // Set Form Action
        $('#deleteForm').attr('action', deleteRoute.replace(':id', id));
        $('#delete-jenis').text(jenis);
    });
});
</script>
@endpush

@push('styles')
<style>
    /* Custom CSS untuk memastikan garis per kolom terlihat jelas */
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6 !important;
    }

    .table thead th { 
        background-color: #343a40; 
        color: #fff; 
        border-color: #454d55;
    }
    .btn-sm { padding: 0.25rem 0.5rem; }
</style>
@endpush