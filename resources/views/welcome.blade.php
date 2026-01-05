@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

<div class="card border-0 rounded-0 mb-5 position-relative overflow-hidden">
    <div style="background: linear-gradient(135deg, #f5f5f5 0%, #fff 100%); min-height: 300px;" class="d-flex align-items-center justify-content-center">
        <div class="text-center p-4" style="max-width: 600px; z-index: 1;">
            <h1 class="display-4 font-serif text-uppercase mb-3">Selamat Datang</h1>
            <p class="text-muted mb-4 small">Temukan keanggunan dalam setiap jahitan. Koleksi kebaya terbaik untuk momen spesial Anda.</p>
            <a href="{{ url('/katalog') }}" class="btn btn-primary-custom px-5 py-3 fw-bold">LIHAT KOLEKSI</a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
    <h2 class="h4 font-serif text-uppercase mb-0">Terbaru</h2>
    <a href="{{ url('/katalog') }}" class="text-decoration-none text-black small fw-bold">LIHAT SEMUA</a>
</div>

<div class="row row-cols-2 row-cols-md-4 g-3">
    @foreach ($featuredProducts as $product)
        <div class="col">
            <div class="card h-100 border rounded-0 product-card">
                <div class="ratio ratio-1x1 bg-light">
                    <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}">
                </div>
                <div class="card-body p-2 text-center">
                    <h5 class="card-title fs-6 font-serif text-truncate">{{ $product->name }}</h5>
                    <div class="fw-bold small">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    <div class="d-grid mt-2"><a href="{{ route('katalog.show', $product->id) }}" class="btn btn-outline-custom w-100 rounded-0 py-1" style="font-size: 0.7rem;">DETAIL</a></div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection