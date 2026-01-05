@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Manajemen Kategori</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola kategori produk</p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary-custom rounded-0 w-100 w-md-auto py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
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
        
        <div class="category-list-header">
            <div class="col-name">Info Kategori</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="category-list-body">
            @forelse($categories as $category)
                <div class="category-list-item">
                    <div class="col-name">
                        <h6 class="fw-bold text-black text-uppercase mb-1" style="font-size: 0.9rem;">{{ $category->name }}</h6>
                        <p class="text-muted small mb-0" style="line-height: 1.4;">{{ $category->description ?? '-' }}</p>
                    </div>

                    <div class="col-actions">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-action btn-edit"><i class="bi bi-pencil me-1"></i> Edit</a>

                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-delete">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-folder-x fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada kategori.</h6>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection