@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 fw-bold text mb-4">Selamat Datang di Adella Kebaya</h1>
    <p class="lead text-muted mb-4">Cabang {{ session('selected_branch')->city }}</p>
    
    <!-- Section Diskon Aktif -->
    @php
        $activeDiscount = \App\Models\Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
    @endphp
    
    @if($activeDiscount)
    <div class="alert alert-warning alert-dismissible fade show mb-4 mx-auto" style="max-width: 800px;" role="alert">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="bi bi-percent display-4 text-warning"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h4 class="alert-heading mb-1">🎉 DISKON SPESIAL! 🎉</h4>
                <p class="mb-1">
                    <strong class="fs-3">{{ $activeDiscount->name }}</strong>
                </p>
                <p class="mb-2">
                    @if($activeDiscount->type === 'percentage')
                    Dapatkan diskon <span class="badge bg-danger fs-6">{{ $activeDiscount->amount }}%</span> untuk semua produk!
                    @else
                    Dapatkan potongan <span class="badge bg-danger fs-6">Rp {{ number_format($activeDiscount->amount, 0, ',', '.') }}</span>!
                    @endif
                </p>
                <small class="text-muted">
                    <i class="bi bi-calendar me-1"></i>
                    Periode: {{ $activeDiscount->start_date->format('d M Y') }} - {{ $activeDiscount->end_date->format('d M Y') }}
                    @if($activeDiscount->max_usage)
                    • Tersisa: {{ $activeDiscount->max_usage - $activeDiscount->used_count }} kuota
                    @endif
                </small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-center gap-3 mt-3">
        <a href="{{ url('/katalog') }}" class="btn btn-lg px-4">KATALOG</a>
        <a href="{{ route('rent.create') }}" class="btn btn-outline-primary btn-lg px-4">SEWA KEBYA</a>
    </div>
</div>

<!-- Produk Terbaru -->
<div class="mt-5">
    <h2 class="text-center fw-bold text mb-4">Produk Terbaru</h2>
    <div class="row g-4">
        @foreach ($featuredProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 hover-shadow border-0">
                    @if($product->is_discounted)
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-danger py-2 px-3">
                            <i class="bi bi-percent"></i> 
                            @if($activeDiscount)
                                {{ $activeDiscount->amount }}{{ $activeDiscount->type === 'percentage' ? '%' : 'K' }}
                            @else
                                DISKON
                            @endif
                        </span>
                    </div>
                    @endif
                    
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold">{{ $product->name }}</h5>
                        
                        <!-- Harga Beli -->
                        <div class="mb-2">
                            <small class="text-muted">Beli:</small>
                            <div>
                                @if($product->is_discounted)
                                <span class="text-decoration-line-through text-muted small">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <br>
                                <span class="fw-bold text fs-5">
                                    Rp {{ number_format($product->discounted_price, 0, ',', '.') }}
                                </span>
                                @else
                                <span class="fw-bold text fs-5">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Harga Sewa -->
                        @if($product->is_available_for_rent && $product->rent_price_per_day)
                        <div class="mb-3">
                            <small class="text-muted">Sewa/hari:</small>
                            <div>
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}
                                </span>
                                @if($product->is_discounted)
                                <br>
                                <small class="text-success">
                                    <i class="bi bi-arrow-down"></i> Diskon berlaku
                                </small>
                                @endif
                                <small class="text-muted d-block">
                                    Min. {{ $product->min_rent_days }} hari
                                </small>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Rating -->
                        @if($product->average_rating > 0)
                        <div class="mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($product->average_rating))
                                <i class="bi bi-star-fill text-warning"></i>
                                @elseif($i - 0.5 <= $product->average_rating)
                                <i class="bi bi-star-half text-warning"></i>
                                @else
                                <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                            <small class="text-muted ms-1">({{ number_format($product->average_rating, 1) }})</small>
                        </div>
                        @endif
                        
                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ url('/katalog/detail/' . $product->id) }}" class="btn btn-sm flex-fill">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection