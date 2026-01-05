@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Daftar Produk</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola inventaris dan katalog produk</p>
        </div>

        <div class="col-md-6">
            <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-center gap-3">
                <form action="{{ route('admin.products.index') }}" method="GET" class="w-100 w-md-auto position-relative" style="min-width: 250px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-0 border-0 border-bottom border-secondary bg-transparent ps-0 pe-5" placeholder="Cari produk..." style="font-size: 0.85rem;">
                    <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent p-0 text-muted">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary-custom rounded-0 py-3 px-4 text-uppercase fw-bold w-100 w-md-auto text-nowrap small" style="letter-spacing: 0.1em;">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Produk
                </a>
            </div>
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
        <div class="product-list-header d-none d-lg-flex">
            <div class="col-thumb">Img</div>
            <div class="col-name">Nama Produk</div>
            <div class="col-category">Kategori</div>
            <div class="col-price">Harga</div>
            <div class="col-stock">Stok</div>
            <div class="col-status">Status</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="product-list-body">
            @forelse($products as $product)
                <div class="product-list-item">
                    
                    <div class="col-thumb">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                    </div>

                    <div class="col-name">
                        <h6 class="fw-bold text-black text-uppercase mb-1" style="font-size: 0.85rem;">{{ $product->name }}</h6>
                        <span class="d-lg-none badge rounded-0 bg-light text-muted border mb-2" style="font-size: 0.6rem;">{{ $product->category->name }}</span>
                    </div>

                    <div class="col-category d-none d-lg-block">
                        <span class="badge rounded-0 bg-white text-muted border text-uppercase" style="font-size: 0.65rem;">{{ $product->category->name }}</span>
                    </div>

                    <div class="col-price">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Harga</span>
                        <span class="fw-bold text-black small">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="col-stock">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Stok</span>
                        <span class="small fw-bold">{{ $product->stock }}</span>
                    </div>

                    <div class="col-status">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Status</span>
                        @if($product->stock == 0)
                            <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-white text-danger border-danger" style="font-size: 0.6rem;">Stok Habis</span>
                        @elseif($product->is_available && $product->is_available_for_rent)
                            <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-black text-white border-black" style="font-size: 0.6rem;">Jual & Sewa</span>
                        @elseif($product->is_available)
                            <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-white text-black border-black" style="font-size: 0.6rem;">Jual</span>
                        @elseif($product->is_available_for_rent)
                            <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-light text-dark border-secondary" style="font-size: 0.6rem;">Sewa</span>
                        @else
                            <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-white text-muted border-secondary" style="font-size: 0.6rem;">Non-Aktif</span>
                        @endif
                    </div>

                    <div class="col-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-action btn-edit">
                            <i class="bi bi-pencil"></i> <span class="d-lg-none ms-2">Edit</span>
                        </a>

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-delete">
                                <i class="bi bi-trash"></i> <span class="d-lg-none ms-2">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-box-seam fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Tidak ada produk ditemukan.</h6>
                </div>
            @endforelse
        </div>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection