@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 fw-bold text mb-4">Selamat Datang di Adella Kebaya</h1>
    <p class="lead text-muted mb-4">Temukan koleksi kebaya terbaik untuk acara spesialmu</p>
    <a href="{{ url('/katalog') }}" class="btn btn-md px-4 mt-3">KATALOG</a>
</div>

<div class="mt-2">
    <h2 class="text-center fw-bold text mb-4">Produk Terbaru</h2>
    <div class="row g-4">
        @foreach ($featuredProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 hover-shadow border-0">
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold">{{ $product->name }}</h5>
                        <p class="card-text text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="{{ url('/katalog/detail/' . $product->id) }}" class="btn mt-auto">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection