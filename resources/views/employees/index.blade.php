@extends('layouts.app')
@section('title', 'Pegawai')
@section('content')
<div class="store-page-header">
    <div>
        <h1>Pegawai</h1>
        <p class="text-secondary small mb-0">Data personel yang mengelola toko dan transaksi.</p>
    </div>
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Pegawai</a>
    @endif
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#4e73df"><div class="card-body">
        <div class="store-metric-label">Total Pegawai</div><div class="store-metric-value">{{ number_format($employees->total()) }}</div>
    </div></div></div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, jabatan, atau telepon"></div>
            <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-search me-1"></i>Cari</button></div>
        </form>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Jabatan</th><th>Tanggal Masuk</th><th>Status</th>@if(Auth::user()->role === 'admin')<th>Aksi</th>@endif</tr></thead>
                <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td class="fw-semibold">{{ $employee->name }}</td>
                        <td>{{ $employee->email ?? '-' }}</td>
                        <td>{{ $employee->phone ?? '-' }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->hire_date?->format('d/m/Y') ?? '-' }}</td>
                        <td><span class="badge {{ $employee->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $employee->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                        @if(Auth::user()->role === 'admin')
                            <td>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data pegawai.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $employees->links() }}
    </div>
</div>
@endsection
