@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container pb-4">
    <div class="text-center mb-5 border-bottom pb-5" style="border-color: #F0F0F0 !important;">
        <h1 class="display-4 fw-normal text-uppercase text-black mb-3" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">
            {{ $category->name }}
        </h1>
        
        <p class="text-muted small text-uppercase mb-2" style="letter-spacing: 0.1em;">
            Koleksi <strong>{{ session('selected_branch')->name }}</strong>
        </p>
        
        @if($category->description)
            <p class="text-muted fw-light mt-3 mx-auto" style="max-width: 600px; line-height: 1.8;">
                {{ $category->description }}
            </p>
        @endif

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
</div>

<div class="container pb-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse ($products as $product)
            <div class="col">
                <div class="card h-100 border rounded-0 position-relative product-card bg-white" style="border-color: #F0F0F0;">
                    
                    {{-- LOGIKA BARU: Overlay TIDAK TERSEDIA --}}
                    @if($product->stock <= 0 || (!$product->is_available && !$product->is_available_for_rent))
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 20;">
                            <span class="badge bg-black text-white rounded-0 px-3 py-2 text-uppercase" style="letter-spacing: 0.1em;">TIDAK TERSEDIA</span>
                        </div>
                    @endif

                    @if($product->is_discounted && $product->is_available)
                        <span class="position-absolute top-0 start-0 badge bg-black text-white m-3 rounded-0 shadow-sm" style="font-weight: 500; letter-spacing: 0.1em; padding: 0.5em 1em; z-index: 10;">PROMO</span>
                    @endif

                    <div class="ratio ratio-1x1 bg-subtle overflow-hidden">
                        <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" style="height: 280px;">
                    </div>
                    
                    <div class="card-body d-flex flex-column p-4 text-center">
                        <h5 class="card-title fw-normal font-serif text-truncate mb-3 text-black" style="font-size: 1rem; letter-spacing: 0.05em;">{{ $product->name }}</h5>
                        
                        <div class="mt-auto">
                            {{-- LOGIKA HARGA --}}
                            @if($product->is_available)
                                {{-- Jika Dijual --}}
                                @if($product->is_discounted)
                                    <div class="text-decoration-line-through text-muted small mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="fw-bold text-black">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="fw-bold text-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                @endif
                            @elseif($product->is_available_for_rent)
                                {{-- Jika Hanya Sewa --}}
                                <div class="fw-bold text-black">Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }} <span class="fw-normal text-muted small">/hari</span></div>
                            @else
                                <div class="fw-bold text-muted">-</div>
                            @endif

                            {{-- INFO TAMBAHAN JIKA JUAL & SEWA --}}
                            @if($product->is_available && $product->is_available_for_rent)
                                <div class="small text-muted mt-2 pt-2 border-top" style="border-color: #eee !important; font-size: 0.7rem;">
                                    Sewa: Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}/hari
                                </div>
                            @endif

                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('katalog.show', $product->id) }}" class="btn btn-outline-custom w-100 rounded-0 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.1em; padding: 10px;">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center border" style="border-style: dashed !important; border-color: #E0E0E0 !important;">
                <i class="bi bi-bag-x display-4 text-muted mb-3 d-block"></i>
                <p class="text-muted text-uppercase mb-4" style="letter-spacing: 0.1em;">Koleksi {{ $category->name }} belum tersedia di cabang ini.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-primary-custom rounded-0 px-4 py-2 text-uppercase" style="letter-spacing: 0.1em; font-size: 0.8rem;">Lihat Koleksi Lain</a>
            </div>
        @endforelse
    </div>

    <div class="mt-5 pt-4 text-center">
        <a href="{{ url('/katalog') }}" class="btn btn-link text-decoration-none text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.15em;">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Katalog
        </a>
    </div>
</div>
@endsection