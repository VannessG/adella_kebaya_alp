@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pegawai</h1>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pegawai
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Cabang</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $employee->nik }}</code></td>
                            <td>{{ $employee->name }}</td>
                            <td><span class="badge bg-info text-dark">{{ $employee->branch->name ?? '-' }}</span></td>
                            <td>{{ $employee->phone }}</td>
                            <td>
                                <span class="badge {{ $employee->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $employee->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Hapus pegawai ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4">Belum ada data pegawai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection