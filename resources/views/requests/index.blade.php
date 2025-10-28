{{-- Menggunakan layout standar L5.5 (biasanya layouts.app) --}}
@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default"> {{-- Menggunakan panel Bootstrap --}}
                <div class="panel-heading">
                    Approval Request Barang 
                </div>

                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->has('error'))
                         <div class="alert alert-danger">
                             {{ $errors->first('error') }}
                         </div>
                    @endif

                    <div class="table-responsive"> {{-- Agar tabel bisa di-scroll di layar kecil --}}
                        <table class="table table-bordered table-striped table-hover"> {{-- Kelas tabel Bootstrap --}}
                            <thead>
                                <tr>
                                    <th>Peminta</th>
                                    <th>NIP</th>
                                    <th>No HP</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $request)
                                <tr>
                                    {{-- Menampilkan nama user jika ada, jika tidak nama pemohon tamu --}}
                                    <td>{{ $request->user ? $request->user->name : $request->nama_pemohon }}</td>
                                    <td>{{ $request->nip ?? '-' }}</td>
                                    <td>{{ $request->no_hp ?? '-' }}</td>
                                    {{-- Pastikan relasi 'item' di-load (sudah di controller) --}}
                                    <td>{{ $request->item ? $request->item->nama_barang : 'N/A' }}</td>
                                    <td>{{ $request->jumlah_request }}</td>
                                    {{-- Format tanggal menggunakan Carbon (otomatis di L5.5) --}}
                                    <td>{{ $request->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="label label-warning">Pending</span> {{-- Label Bootstrap --}}
                                        @elseif($request->status == 'approved')
                                            <span class="label label-success">Approved</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="label label-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;"> {{-- Agar tombol tidak wrap --}}
                                        @if($request->status == 'pending')
                                            {{-- Tombol Approve --}}
                                            <form action="{{ route('requests.approve', $request->id) }}" method="POST" style="display: inline-block;">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }} {{-- Method spoofing di L5.5 --}}
                                                <button type="submit" class="btn btn-xs btn-success"> {{-- Tombol Bootstrap --}}
                                                    <i class="fa fa-check"></i> Approve {{-- Font Awesome (jika ada) --}}
                                                </button>
                                            </form>

                                            {{-- Tombol Reject --}}
                                            <form action="{{ route('requests.reject', $request->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menolak request ini?')">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fa fa-times"></i> Reject
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada permintaan barang yang masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link Paginasi Bootstrap --}}
                    <div class="text-center">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
