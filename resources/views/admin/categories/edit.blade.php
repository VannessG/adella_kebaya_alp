@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')

{{-- HEADER SECTION --}}
<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Edit Kategori</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Perbarui informasi kategori produk</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Kategori --}}
                    <div class="mb-4">
                        <label for="name" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Kategori</label>
                        <input type="text" class="form-control rounded-0 bg-subtle border-0 p-3 @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-5">
                        <label for="description" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Deskripsi</label>
                        <textarea class="form-control rounded-0 bg-subtle border-0 p-3 @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-3 text-uppercase fw-bold flex-grow-1 flex-md-grow-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-custom rounded-0 px-4 py-3 text-uppercase fw-bold flex-grow-1 flex-md-grow-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection