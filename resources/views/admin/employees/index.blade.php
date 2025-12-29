@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Daftar Pegawai</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola data karyawan dan staf</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            {{-- TOMBOL CREATE --}}
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary-custom rounded-0 py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Pegawai
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4 p-3" role="alert">
            <i class="bi bi-check-circle me-3 fs-5"></i>
            <div class="small text-uppercase" style="letter-spacing: 0.05em;">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABEL DATA --}}
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">No</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">NIK</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Nama</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Cabang</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.9rem;">Telepon</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.9rem;">Status</th>
                            <th class="pe-4 py-3 text-uppercase small text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.9rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td class="ps-4 py-3 text-muted small">{{ $loop->iteration }}</td>
                            <td class="py-3">
                                <span class="font-monospace small text-black fw-bold">{{ $employee->nik }}</span>
                            </td>
                            <td class="py-3">
                                <span class="text-black fw-bold text-uppercase small" style="letter-spacing: 0.05em;">{{ $employee->name }}</span>
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-0 border text-black bg-white px-2 py-1 text-uppercase" 
                                      style="font-size: 0.8rem; letter-spacing: 0.05em; border-color: #ddd;">
                                    {{ $employee->branch->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 text-start">{{ $employee->phone }}</td>
                            <td class="py-3 text-center">
                                <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border" 
                                      style="font-size: 0.8rem; letter-spacing: 0.05em;
                                      @if($employee->is_active) background-color: #000; color: #fff; border-color: #000;
                                      @else background-color: #fff; color: #dc3545; border-color: #dc3545; @endif">
                                    {{ $employee->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.employees.edit', $employee->id) }}" 
                                       class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                       style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Hapus pegawai ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                                style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted small text-uppercase" style="letter-spacing: 0.1em;">Belum ada data pegawai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection