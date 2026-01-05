@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Edit Produk</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Perbarui informasi detail produk</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border rounded-0 bg-white p-4 p-md-5 shadow-sm" style="border-color: var(--border-color);">
                
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Produk</label>
                        <input type="text" class="form-control rounded-0 bg-subtle border-0 p-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kategori</label>
                            <select class="form-select rounded-0 bg-subtle border-0 p-3 @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="branch_id" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Cabang Lokasi</label>
                            <select class="form-select rounded-0 bg-subtle border-0 p-3 @error('branch_id') is-invalid @enderror" 
                                    id="branch_id" name="branch_id" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $product->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="price" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-0 bg-white border-end-0 text-muted small" style="border-color: #eee;">Rp</span>
                                <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="rent_price_per_day" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Harga Sewa / Hari</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-0 bg-white border-end-0 text-muted small" style="border-color: #eee;">Rp</span>
                                <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('rent_price_per_day') is-invalid @enderror" id="rent_price_per_day" name="rent_price_per_day" value="{{ old('rent_price_per_day', $product->rent_price_per_day) }}">
                            </div>
                            @error('rent_price_per_day')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="stock" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Stok</label>
                            <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="weight" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Berat (gram)</label>
                            <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3" name="weight" value="{{ old('weight', $product->weight) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Deskripsi</label>
                        <textarea class="form-control rounded-0 bg-subtle border-0 p-3 @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label for="image" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Gambar Produk</label>
                        @if($product->image)
                            <div class="mb-3 border p-2 d-inline-block bg-white" style="border-color: #eee !important;">
                                <img src="{{ $product->image_url }}" class="d-block" style="max-height: 150px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" class="form-control rounded-0 bg-subtle border-0 p-3" id="image" name="image" accept="image/*">
                        <div class="form-text small text-muted fst-italic mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-4 mb-5 p-4 border bg-subtle" style="border-color: #eee !important;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input rounded-0 border-black" id="is_available" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}>
                            <label class="form-check-label small text-uppercase fw-bold" for="is_available" style="font-size: 0.75rem; letter-spacing: 0.05em; padding-top: 2px;">Tersedia untuk Jual</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input rounded-0 border-black" id="is_available_for_rent" name="is_available_for_rent" value="1" {{ $product->is_available_for_rent ? 'checked' : '' }}>
                            <label class="form-check-label small text-uppercase fw-bold" for="is_available_for_rent" style="font-size: 0.75rem; letter-spacing: 0.05em; padding-top: 2px;">Tersedia untuk Sewa</label>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 pt-2">
                        <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-3 text-uppercase fw-bold w-100 w-md-auto" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-custom rounded-0 px-4 py-3 text-uppercase fw-bold w-100 w-md-auto text-center" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection