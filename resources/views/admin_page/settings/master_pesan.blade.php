@extends('layouts.app')

@section('title', 'Master Template Pesan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Master Template Pesan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan Template Notifikasi Sistem</h6>
        </div>
        <div class="card-body">
            @if (Auth::user()->role === 'admin_barang')
            <div class="alert alert-info">Anda hanya dapat melihat dan mengelola template pesan Bidang Anda (jika diizinkan).</div>
            @endif
            {{-- Form dan Daftar Template Pesan --}}
            <p>Super Admin melihat semua template. Admin Barang hanya melihat template untuk bidang mereka.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">No</th>
                            <th>Bidang</th>
                            <th>Deskripsi Template</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bidangs as $i => $bidang)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $bidang->nama }}</td>
                            <td>
                                @if($bidang->pesan_template)
                                    <div class="text-truncate" style="max-width:600px;">{!! nl2br(e(\Illuminate\Support\Str::limit($bidang->pesan_template, 250))) !!}</div>
                                @else
                                    <span class="text-muted">Belum ada template</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-edit-template" 
                                        data-id="{{ $bidang->id }}" data-name="{{ $bidang->nama }}" data-content="{{ e($bidang->pesan_template) }}">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada data bidang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1" role="dialog" aria-labelledby="editTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('template.update') }}" method="POST" id="templateForm">
                @csrf
                <input type="hidden" name="bidang_id" id="modal_bidang_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTemplateModalLabel">Edit Template Pesan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Bidang</label>
                        <input type="text" id="modal_bidang_name" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modal_content">Isi Template</label>
                        <textarea name="content" id="modal_content" rows="8" class="form-control" placeholder="Gunakan placeholder seperti {nama}, {tanggal}, dsb."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    </div>

@push('scripts')
<script>
$(function(){
    $('.btn-edit-template').click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var content = $(this).data('content');

        $('#modal_bidang_id').val(id);
        $('#modal_bidang_name').val(name);
        $('#modal_content').val(content);

        $('#editTemplateModal').modal('show');
    });
});
</script>
@endpush

@push('styles')
<style>
    .text-truncate { white-space: normal; }
</style>
@endpush
@endsection