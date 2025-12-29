@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="card border-0 overflow-hidden mb-5 rounded-0 position-relative shadow-sm">
    <div style="background: linear-gradient(135deg, #F5F5F5 0%, #FFFFFF 100%); height: 370px; width: 100%;"></div>
    
    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-center p-5">
        <h1 class="display-3 fw-normal mb-3 font-serif text-uppercase text-black" style="letter-spacing: 0.15em;">Selamat Datang</h1>
        <p class="lead mb-5 fw-light text-muted" style="max-width: 600px; font-size: 1rem; letter-spacing: 0.05em;">
            Koleksi kebaya eksklusif untuk momen terindah Anda. <br>
            Butik {{ session('selected_branch')->city ?? 'Online' }}
        </p>
        <div class="d-flex gap-3">
            <a href="{{ url('/katalog') }}" class="btn btn-primary-custom px-5 py-3">LIHAT KOLEKSI</a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3" style="border-color: #E5E5E5 !important;">
    <div>
        <h2 class="fw-normal mb-1 font-serif text-uppercase text-black" style="letter-spacing: 0.1em;">Koleksi Terbaru</h2>
        <p class="text-muted mb-0 small text-uppercase" style="letter-spacing: 0.05em;">Kurasi pilihan minggu ini</p>
    </div>
    <a href="{{ url('/katalog') }}" class="text-link-custom small text-uppercase fw-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">
        Lihat Semua
    </a>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach ($featuredProducts as $product)
        <div class="col">
            <div class="card h-100 border rounded-0 position-relative product-card bg-white" style="border-color: #F0F0F0;">
                @if($product->is_discounted)
                    <span class="position-absolute top-0 start-0 badge bg-black text-white m-3 rounded-0 shadow-sm" style="font-weight: 500; letter-spacing: 0.1em; padding: 0.5em 1em;">PROMO</span>
                @endif
                
                <div class="ratio ratio-1x1 bg-subtle overflow-hidden">
                    <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" style="height: 280px;">
                </div>
                
                <div class="card-body d-flex flex-column p-4 text-center">
                    <small class="text-muted mb-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.15em;">{{ $product->category->name ?? 'Kebaya' }}</small>
                    <h5 class="card-title fw-normal font-serif text-truncate mb-3 text-black" style="font-size: 1rem; letter-spacing: 0.05em;">{{ $product->name }}</h5>
                    
                    <div class="mt-auto">
                        @if($product->is_discounted)
                            <div class="text-decoration-line-through text-muted small mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="fw-bold text-black">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                        @else
                            <div class="fw-bold text-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                        <a href="{{ url('/katalog/detail/' . $product->id) }}" class="btn btn-link text-decoration-none text-black small mt-3 p-2 stretched-link text-uppercase fw-bold border-bottom border-black rounded-0 btn-detail" style="letter-spacing: 0.15em; font-size: 0.7rem; display: inline-block;">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection