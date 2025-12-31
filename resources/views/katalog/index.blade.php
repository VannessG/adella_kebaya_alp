@extends('layouts.app')

@section('title', 'Katalog Kebaya')

@section('content')

<div class="container pb-4 text-center">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Koleksi Eksklusif</h1>
    <div style="width: 40px; height: 1px; background-color: #000; margin: 15px auto;"></div>
    <p class="text-muted small text-uppercase mb-4" style="letter-spacing: 0.2em;">Cabang: <strong>{{ session('selected_branch')->name }}</strong></p>
    <div class="row justify-content-center mb-4">
        <div class="col-md-6 col-lg-4">
            <form action="{{ url('/katalog') }}" method="GET" class="position-relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control rounded-0 border-0 border-bottom border-black bg-transparent ps-2 pe-5 text-center" placeholder="Cari model kebaya..." style="font-size: 0.9rem;">
                <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent p-0">
                    <i class="bi bi-search text-black"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
        <a href="{{ url('/katalog') }}" class="btn btn-filter rounded-0 px-4 py-2 {{ !isset($currentCategoryId) ? 'active' : '' }}">SEMUA</a>
        @foreach($categories as $cat)
            <a href="{{ url('/kategori/' . $cat->id) }}" class="btn btn-filter rounded-0 px-4 py-2 {{ (isset($currentCategoryId) && $currentCategoryId == $cat->id) ? 'active' : '' }}">{{ strtoupper($cat->name) }}</a>
        @endforeach
    </div>
</div>

<div class="container pb-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse ($products as $product)
            <div class="col">
                <div class="card h-100 border rounded-0 position-relative product-card bg-white" style="border-color: #F0F0F0;">
                @if($product->stock <= 0 || (!$product->is_available && !$product->is_available_for_rent))
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 20;">
                        <span class="badge bg-black text-white rounded-0 px-3 py-2 text-uppercase" style="letter-spacing: 0.1em;">TIDAK TERSEDIA</span>
                    </div>
                @endif

                    <div class="ratio ratio-1x1 bg-subtle overflow-hidden">
                        <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" style="height: 280px;">
                    </div>
                    
                    <div class="card-body d-flex flex-column p-4 text-center">
                        <small class="text-muted mb-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.15em;">{{ $product->category->name ?? 'Kebaya' }}</small>
                        <h5 class="card-title fw-normal font-serif text-truncate mb-3 text-black" style="font-size: 1rem; letter-spacing: 0.05em;">{{ $product->name }}</h5>
                        <div class="mt-auto">
                            <div class="fw-bold text-black mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @if($product->is_available_for_rent)
                                <div class="small text-muted mb-3 border-top pt-2" style="border-color: #eee !important;">Sewa: Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}/hari</div>
                            @endif

                            <div class="d-grid gap-2">
                                <a href="{{ route('katalog.show', $product->id) }}" class="btn btn-outline-custom btn-sm rounded-0 px-3 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em; padding: 10px;">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center border" style="border-style: dashed !important; border-color: #E0E0E0 !important;">
                <p class="text-muted text-uppercase mb-0" style="letter-spacing: 0.1em;">Tidak ada produk ditemukan di cabang ini.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection