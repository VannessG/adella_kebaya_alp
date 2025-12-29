@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')

{{-- HEADER SECTION --}}
<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Manajemen Kategori</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola kategori produk sistem (Admin)</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            {{-- TOMBOL CREATE --}}
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom rounded-0 py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
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
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Kategori</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Deskripsi</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-black" style="font-family: 'Jost', sans-serif;">{{ $category->name }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="text-muted small">{{ $category->description ?? '-' }}</span>
                                </td>
                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- TOMBOL EDIT --}}
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                           style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>

                                        {{-- TOMBOL DELETE --}}
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                                    style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted small text-uppercase" style="letter-spacing: 0.1em;">Data kategori admin kosong.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection