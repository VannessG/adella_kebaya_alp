@extends('layouts.app')

@section('title', 'Katalog Kebaya')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold mb-2">Katalog Kebaya</h1>
        <p class="text-muted mb-0">Temukan kebaya impianmu dari koleksi terbaik kami di cabang <strong>{{ session('selected_branch')->name }}</strong></p>
    </div>
    <form action="{{ url('/katalog') }}" method="GET" class="mt-3 mt-md-0 position-relative" style="width: 300px;">
        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control rounded-pill ps-4 pe-5" placeholder="Cari model...">
        <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-2 text-muted border-0 bg-transparent">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm p-3 border-0 rounded-4">
            <h5 class="fw-bold mb-3 px-2">Kategori</h5>
            <div class="list-group list-group-flush">
                {{-- Link Semua Produk --}}
                <a href="{{ url('/katalog') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1 {{ !isset($currentCategoryId) ? 'active bg-primary text-white fw-bold' : '' }}">Semua Produk</a>
                
                {{-- Link Per Kategori --}}
                @foreach($categories as $cat)
                    <a href="{{ url('/kategori/' . $cat->id) }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1 {{ (isset($currentCategoryId) && $currentCategoryId == $cat->id) ? 'active bg-primary text-white fw-bold' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @forelse ($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card rounded-4">
                        <div class="position-relative overflow-hidden rounded-top-4">
                            <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" style="height: 280px;">
                            {{-- Jika stok 0 atau is_available false --}}
                            @if($product->stock <= 0 || !$product->is_available)
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center text-white fw-bold text-center p-2">PRODUK TIDAK TERSEDIA</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ $product->category->name }}</small>
                            <h5 class="card-title fw-bold mt-1">{{ $product->name }}</h5>
                            <div class="mb-3">
                                <span class="fw-bold text-primary fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($product->is_available_for_rent)
                                <div class="mt-2 pt-2 border-top">
                                    <small class="text-success fw-semibold"><i class="bi bi-calendar-event me-1"></i> Sewa: Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}/hari</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-4 px-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('katalog.show', $product->id) }}" class="btn btn-outline-dark rounded-pill btn-sm">Lihat Detail</a>
                                
                                @if($product->is_available_for_rent && $product->is_available && $product->stock > 0)
                                    <a href="{{ route('rent.create', $product->id) }}" class="btn btn-primary-custom rounded-pill btn-sm">Sewa Sekarang</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <p class="mt-3 text-muted">Tidak ada produk ditemukan di cabang ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection