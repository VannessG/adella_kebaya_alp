@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="card border-0 overflow-hidden mb-5 rounded-0 position-relative shadow-sm" style="background-color: var(--bg-surface);">
    <div style="background: linear-gradient(45deg, #E6DED5, #FAF3E0); height: 450px; width: 100%;"></div>
    
    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-center p-4">
        <h1 class="display-3 fw-normal mb-3 font-serif" style="color: var(--text-main); letter-spacing: 2px;">Elegan dalam Tradisi</h1>
        <p class="lead mb-4 fw-light" style="max-width: 600px; color: var(--text-muted); font-size: 1.1rem;">
            Temukan koleksi kebaya eksklusif untuk momen terindah Anda. Tersedia untuk sewa dan beli di butik kami di {{ session('selected_branch')->city ?? 'Kota Anda' }}.
        </p>
        <div class="d-flex gap-3">
            <a href="{{ url('/katalog') }}" class="btn btn-primary-custom px-5 py-3 fw-bold" style="letter-spacing: 2px;">LIHAT KOLEKSI</a>
        </div>
    </div>
</div>

@if($activeDiscount)
<div class="alert border-0 shadow-sm rounded-0 d-flex align-items-center justify-content-between p-4 mb-5" role="alert" 
     style="background-color: var(--accent-yellow); border-left: 4px solid #E3C08D;">
    <div class="d-flex align-items-center">
        <div class="text-dark rounded-circle d-flex align-items-center justify-content-center me-4" 
             style="width: 60px; height: 60px; background-color: rgba(227, 192, 141, 0.2);">
            <i class="bi bi-tag-fill fs-3" style="color: #B08D55;"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1 font-serif text-dark">{{ $activeDiscount->name }}</h5>
            <p class="mb-0 text-muted small">
                Dapatkan Potongan 
                @if($activeDiscount->type === 'percentage')
                    <span class="fw-bold text-dark">{{ $activeDiscount->amount }}%</span>
                @else
                    <span class="fw-bold text-dark">Rp {{ number_format($activeDiscount->amount, 0, ',', '.') }}</span>
                @endif
                untuk semua item. Penawaran berakhir {{ $activeDiscount->end_date->format('d M Y') }}.
            </p>
        </div>
    </div>
    <a href="{{ url('/katalog') }}" class="btn btn-outline-custom px-4 rounded-0" style="font-size: 0.75rem;">AMBIL PROMO</a>
</div>
@endif

{{-- SECTION TITLE --}}
<div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3" style="border-color: var(--border-color) !important;">
    <div>
        <h2 class="fw-normal mb-1 font-serif text-uppercase" style="letter-spacing: 1px;">Koleksi Terbaru</h2>
        <p class="text-muted mb-0 small">Kurasi pilihan terbaik minggu ini</p>
    </div>
    <a href="{{ url('/katalog') }}" class="text-decoration-none fw-bold small text-uppercase" style="color: var(--text-main); letter-spacing: 1px;">
        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>

{{-- PRODUCT GRID --}}
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach ($featuredProducts as $product)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm rounded-0 position-relative product-card" style="background-color: var(--bg-surface);">
                
                {{-- Badge Promo (Jika Ada) --}}
                @if($product->is_discounted)
                    <span class="position-absolute top-0 start-0 badge bg-white text-dark m-3 rounded-0 shadow-sm" 
                          style="font-weight: 500; letter-spacing: 1px; border: 1px solid #EEE;">PROMO</span>
                @endif
                
                {{-- Product Image --}}
                <div class="ratio ratio-1x1 bg-light overflow-hidden">
                    <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" 
                         style="transition: transform 0.5s ease;">
                </div>
                
                <div class="card-body d-flex flex-column p-4 text-center">
                    <small class="text-muted mb-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">{{ $product->category->name ?? 'Kebaya' }}</small>
                    <h5 class="card-title fw-normal font-serif text-truncate mb-3" style="font-size: 1.1rem;">{{ $product->name }}</h5>
                    
                    <div class="mt-auto">
                        @if($product->is_discounted)
                            <div class="text-decoration-line-through text-muted small mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="fw-bold text-dark">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                        @else
                            <div class="fw-bold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                        
                        <a href="{{ url('/katalog/detail/' . $product->id) }}" class="btn btn-link text-decoration-none text-muted small mt-2 p-0 stretched-link">LIHAT DETAIL</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection