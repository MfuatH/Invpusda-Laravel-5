@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <h1 class="h3 mb-4 text-gray-800">Manajemen User</h1>

    @if (Auth::user()->role !== 'super_admin')
        <div class="alert alert-danger">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </div>
    @else
        <!-- Card Utama -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white border-0">
                <h6 class="m-0 font-weight-bold text-primary"></h6>
                <a href="{{ route('super.users.create') }}" class="btn btn-success btn-sm">
                    + Tambah User
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" width="100%" cellspacing="0">
                        <thead style="background-color: #1e293b; color: white;">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Role</th>
                                <th>Bidang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->no_hp ?? '-' }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ isset($user->bidang->nama) ? $user->bidang->nama : '-' }}</td>
                                    <td>
                                        <a href="{{ route('super.users.edit', $user->id) }}" 
                                           style="color: #facc15; text-decoration: none; font-weight: 600; margin-right: 8px;">
                                            Edit
                                        </a>
                                        @if ($user->role !== 'super_admin')
                                            <form action="{{ route('super.users.destroy', $user->id) }}" 
                                                  method="POST" style="display:inline;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" 
                                                        onclick="return confirm('Yakin ingin menghapus pengguna ini?')"
                                                        style="color: #ef4444; background: none; border: none; font-weight: 600; cursor: pointer;">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
