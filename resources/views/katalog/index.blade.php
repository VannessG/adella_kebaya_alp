@extends('layouts.app')

@section('title', 'Katalog Kebaya')

@section('content')

<div class="container pb-4 text-center">
    <h1 class="section-title mb-3">Koleksi Eksklusif</h1>
    <div style="width: 40px; height: 1px; background-color: #000; margin: 0 auto 15px;"></div>
    <p class="subtitle text-uppercase small mb-4">Cabang: <strong>{{ session('selected_branch')->name }}</strong></p>
    
    <div class="row justify-content-center mb-4">
        <div class="col-10 col-md-6 col-lg-4">
            <form action="{{ url('/katalog') }}" method="GET" class="position-relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control rounded-0 border-0 border-bottom border-black bg-transparent ps-2 pe-5 text-center" placeholder="Cari model kebaya...">
                <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent p-0">
                    <i class="bi bi-search text-black"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="d-flex justify-content-start justify-content-md-center gap-2 flex-nowrap overflow-auto pb-2 px-1" style="scrollbar-width: none;">
        <a href="{{ url('/katalog') }}" class="btn btn-filter text-nowrap rounded-0 px-4 {{ !isset($currentCategoryId) ? 'active' : '' }}">SEMUA</a>
        @foreach($categories as $cat)
            <a href="{{ url('/kategori/' . $cat->id) }}" class="btn btn-filter text-nowrap rounded-0 px-4 {{ (isset($currentCategoryId) && $currentCategoryId == $cat->id) ? 'active' : '' }}">{{ strtoupper($cat->name) }}</a>
        @endforeach
    </div>
</div>

<div class="container pb-5">
    <div class="row row-cols-2 row-cols-md-4 g-3">
        @forelse ($products as $product)
            <div class="col">
                <div class="card h-100 border rounded-0 product-card position-relative">
                    
                    @if($product->stock <= 0 || (!$product->is_available && !$product->is_available_for_rent))
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 20;">
                            <span class="badge bg-black text-white rounded-0 px-2 py-1 small">HABIS</span>
                        </div>
                    @endif

                    <div class="ratio ratio-1x1 bg-light overflow-hidden">
                        <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}">
                    </div>
                    
                    <div class="card-body d-flex flex-column px-2 py-3 px-md-3 text-center">
                        <small class="text-muted mb-1 text-uppercase" style="font-size: 0.6rem;">{{ $product->category->name }}</small>
                        <h5 class="card-title fw-normal font-serif text-truncate mb-2" style="font-size: 0.9rem;">{{ $product->name }}</h5>
                        <div class="mt-auto">
                            <div class="fw-bold text-black mb-1" style="font-size: 0.85rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="d-grid mt-2">
                                <a href="{{ route('katalog.show', $product->id) }}" class="btn btn-outline-dark w-100 rounded-0 py-1 text-uppercase fw-bold" style="font-size: 0.7rem; border: 1px solid #000;">DETAIL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <p class="text-muted small text-uppercase">Tidak ada produk ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection