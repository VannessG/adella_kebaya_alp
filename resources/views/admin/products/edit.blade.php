@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold text mb-4">Edit Produk</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Cabang Lokasi</label>
                            <select class="form-select" name="branch_id" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $product->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Harga Jual (Beli)</label>
                                    <input type="number" class="form-control" name="price" value="{{ old('price', $product->price) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rent_price_per_day" class="form-label">Harga Sewa / Hari</label>
                                    <input type="number" class="form-control" name="rent_price_per_day" value="{{ old('rent_price_per_day', $product->rent_price_per_day) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stok</label>
                                    <input type="number" class="form-control" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Berat (gram)</label>
                                    <input type="number" class="form-control" name="weight" value="{{ old('weight', $product->weight) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ $product->image_url }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="d-flex gap-4 mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Tersedia untuk Jual</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_available_for_rent" name="is_available_for_rent" value="1" {{ $product->is_available_for_rent ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available_for_rent">Tersedia untuk Sewa</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">Update Produk</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection