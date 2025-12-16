@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="card bg-dark text-white border-0 overflow-hidden mb-5 rounded-4 position-relative">
    <div style="background: linear-gradient(45deg, #3a2611, #8B5E3C); height: 400px; width: 100%;"></div>
    
    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-center p-4" style="background: rgba(0,0,0,0.3);">
        <h1 class="display-3 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Elegan dalam Tradisi</h1>
        <p class="lead mb-4" style="max-width: 600px;">Temukan koleksi kebaya eksklusif untuk momen terindah Anda. Tersedia untuk sewa dan beli di cabang {{ session('selected_branch')->city }}.</p>
        <div class="d-flex gap-3">
            <a href="{{ url('/katalog') }}" class="btn btn-light rounded-pill px-4 py-2 fw-semibold">Lihat Katalog</a>
            <a href="{{ route('rent.create') }}" class="btn btn-outline-light rounded-pill px-4 py-2">Mulai Sewa</a>
        </div>
    </div>
</div>

@php
    $activeDiscount = \App\Models\Discount::where('is_active', true)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->first();
@endphp

@if($activeDiscount)
<div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center justify-content-between p-4 mb-5" role="alert" style="background-color: #FFF8E1;">
    <div class="d-flex align-items-center">
        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="bi bi-percent fs-3"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0 text-dark">{{ $activeDiscount->name }}</h5>
            <p class="mb-0 text-muted small">
                Diskon 
                @if($activeDiscount->type === 'percentage')
                    <span class="badge bg-danger">{{ $activeDiscount->amount }}%</span>
                @else
                    <span class="badge bg-danger">Rp {{ number_format($activeDiscount->amount, 0, ',', '.') }}</span>
                @endif
                All Items! Berakhir {{ $activeDiscount->end_date->format('d M Y') }}.
            </p>
        </div>
    </div>
    <a href="{{ url('/katalog') }}" class="btn btn-warning rounded-pill px-4 text-white fw-bold shadow-sm">Ambil Promo</a>
</div>
@endif

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold mb-1">Koleksi Terbaru</h2>
        <p class="text-muted mb-0">Pilihan terbaik minggu ini untuk Anda</p>
    </div>
    <a href="{{ url('/katalog') }}" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach ($featuredProducts as $product)
        <div class="col">
            <div class="card h-100 product-card position-relative">
                @if($product->is_discounted)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3 rounded-pill shadow-sm">Promo</span>
                @endif
                
                <div class="ratio ratio-1x1 bg-light">
                    <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}">
                </div>
                
                <div class="card-body d-flex flex-column">
                    <small class="text-muted mb-1">{{ $product->category->name ?? 'Kebaya' }}</small>
                    <h5 class="card-title fw-bold text-truncate">{{ $product->name }}</h5>
                    
                    <div class="mt-auto pt-3 d-flex justify-content-between align-items-end">
                        <div>
                            @if($product->is_discounted)
                                <small class="text-decoration-line-through text-muted" style="font-size: 0.8rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                <div class="fw-bold text-danger">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                            @else
                                <div class="fw-bold" style="color: var(--primary-color);">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                        <a href="{{ url('/katalog/detail/' . $product->id) }}" class="btn btn-outline-custom btn-sm rounded-circle" style="width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection