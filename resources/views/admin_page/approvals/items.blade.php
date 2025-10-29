@extends('layouts.app')

@section('title', 'Approval Permintaan Barang')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">{{ $pageTitle ?? 'Approval Permintaan Barang' }}</h1>

    @if (Auth::user()->role === 'admin_barang')
    <div class="alert alert-warning" role="alert">
        Anda hanya melihat daftar permintaan barang dari Bidang Anda saja.
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Permintaan Barang Pending</h6>
        </div>
        <div class="card-body">
            @if ($requests->isEmpty())
                <p class="text-center">Tidak ada permintaan barang yang menunggu persetujuan saat ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Peminta</th>
                                <th>Bidang</th>
                                <th>Item Diminta</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->user_name }}</td>
                                <td>{{ $request->bidang }}</td>
                                <td>[List Item & Quantity]</td>
                                <td>
                                    <form action="{{ route('requests.approve', $request->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Setujui</button>
                                        <button type="button" class="btn btn-danger btn-sm">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection