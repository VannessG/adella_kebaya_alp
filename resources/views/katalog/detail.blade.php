@extends('layouts.app')

@section('title', 'Detail Kebaya')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image_url }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            
            @if($product->reviews->count() > 0)
            <div class="mt-4">
                <h5 class="fw-bold">Review Produk</h5>
                <div class="d-flex align-items-center mb-2">
                    <div class="text-warning me-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="text-muted">({{ $product->total_reviews }} review)</span>
                </div>
                <a href="{{ route('review.product', $product->id) }}" class="btn btn-outline-primary btn-sm">
                    Lihat Semua Review
                </a>
            </div>
            @endif
        </div>
        
        <div class="col-md-6">
            <h2 class="fw-bold text mb-3">{{ $product->name }}</h2>
            <span class="badge bg mb-3">{{ $category->name }}</span>
            <p class="text-muted mb-4">{{ $product->description }}</p>

            <div class="mb-4">
                <div class="row">
                    <div class="col-6">
                        <h4 class="text fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                        <small class="text-muted">Harga Beli</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success fw-bold">Rp {{ number_format($product->rent_price, 0, ',', '.') }}/hari</h4>
                        <small class="text-muted">Harga Sewa</small>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Stok: {{ $product->stock }} pcs</small>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4">
                @auth
                    @if (auth()->user()->role === 'user')
                        @if ($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn px-4">+ Keranjang</button>
                            </form>
                            <a href="{{ route('checkout.form') }}?product={{ $product->id }}&quantity=1" class="btn btn-outline-primary px-4">Beli Sekarang</a>
                            <a href="{{ route('rent.create.product', ['product' => $product->id]) }}" class="btn btn-outline-primary px-4">Sewa</a>
                        @else
                            <button class="btn btn-secondary px-4" disabled>Stok Habis</button>
                        @endif
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn px-4">+ Keranjang</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-4">Beli Sekarang</a>
                    <a href="{{ route('login') }}" class="btn btn-success px-4">Sewa</a>
                @endguest
            </div>

            @if($product->rent_price > 0)
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                <strong>Fitur Sewa Tersedia!</strong> Sewa kebaya ini dengan harga terjangkau. 
                Pilih periode sewa dan nikmati kebaya impian Anda untuk acara spesial.
            </div>
            @endif
        </div>
    </div>

    <div class="mt-5 pt-4 border-top">
        <a href="{{ url('/katalog') }}" class="btn btn-outline-secondary">Kembali ke Katalog</a>
    </div>
@endsection