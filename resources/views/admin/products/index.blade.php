@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Daftar Produk</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola inventaris dan katalog produk</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom rounded-0 py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Produk
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Gambar</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Produk</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kategori</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Harga</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Stok</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Status</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-4 py-3">
                                    <div class="border p-1 bg-white" style="width: 60px; height: 60px; border-color: #eee !important;">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover d-block">
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $product->name }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-0 bg-white text-muted border text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.05em;">{{ $product->category->name }}</span>
                                </td>
                                <td class="py-3 fw-bold text-black small">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="py-3 text-center small">{{ $product->stock }}</td>
                                <td class="py-3 text-center">
                                    @if($product->stock == 0)
                                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-white text-danger border-danger" style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                            Stok Habis
                                        </span>
                                    @elseif($product->is_available && $product->is_available_for_rent)
                                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-black text-white border-black" style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                            Jual & Sewa
                                        </span>
                                    @elseif($product->is_available)
                                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-white text-black border-black" style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                            Jual
                                        </span>
                                    @elseif($product->is_available_for_rent)
                                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-light text-dark border-secondary" style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                            Sewa
                                        </span>
                                    @else
                                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-white text-muted border-secondary" style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                           style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                                    style="font-size: 0.65rem; letter-spacing: 0.05em;"
                                                    onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
</div>
@endsection