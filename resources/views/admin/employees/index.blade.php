@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Daftar Pegawai</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                Kelola data karyawan dan staf 
                @if(session('selected_branch'))
                    <span class="text-black fw-bold"> - {{ session('selected_branch')->name }}</span>
                @endif
            </p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary-custom rounded-0 w-100 w-md-auto py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Pegawai
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">

    @if(session('success'))
        <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4 p-3 mx-3 mx-md-0" role="alert">
            <i class="bi bi-check-circle me-3 fs-5"></i>
            <div class="small text-uppercase" style="letter-spacing: 0.05em;">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border rounded-0 shadow-sm bg-white mx-0 mx-md-0">
        <div class="employee-list-header d-none d-lg-flex">
            <div class="col-no">No</div>
            <div class="col-nik">NIK</div>
            <div class="col-name">Nama</div>
            <div class="col-branch">Cabang</div>
            <div class="col-phone">Telepon</div>
            <div class="col-status">Status</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="employee-list-body">
            @forelse($employees as $employee)
                <div class="employee-list-item">
                    
                    <div class="col-no">{{ $loop->iteration }}</div>

                    <div class="col-name">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Nama</span>
                        <span class="text-uppercase text-black">{{ $employee->name }}</span>
                    </div>

                    <div class="col-branch">
                        <span class="badge rounded-0 bg-white text-black border fw-normal text-uppercase" style="font-size: 0.65rem;">{{ $employee->branch->name ?? '-' }}</span>
                    </div>

                    <div class="col-nik">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">NIK</span>{{ $employee->nik }}
                    </div>

                    <div class="col-phone">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Telepon</span>{{ $employee->phone }}
                    </div>

                    <div class="col-status">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Status</span>
                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border" style="font-size: 0.65rem; letter-spacing: 0.05em;
                                @if($employee->is_active) background-color: #000; color: #fff; border-color: #000;
                                @else background-color: #fff; color: #dc3545; border-color: #dc3545; @endif">
                            {{ $employee->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>

                    <div class="col-actions">
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-action btn-edit">
                            <i class="bi bi-pencil me-1"></i> <span class="d-lg-none ms-2">Edit</span>
                        </a>
                        <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pegawai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-delete">
                                <i class="bi bi-trash me-1"></i> <span class="d-lg-none ms-2">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-person-x fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada data pegawai.</h6>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection