@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<h1 class="fw-bold text mb-4 text-center text-md-start">Keranjang Belanja</h1>

@if (!$isEmpty)
    <div class="row gy-3">
        @foreach ($cartItems as $item)
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="row g-0 h-100 flex-column flex-md-row">
                    
                    {{-- Gambar produk --}}
                    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center p-2">
                        <img src="{{ $item['image_url'] ?? $item['image'] }}" 
                             class="img-fluid rounded shadow-sm" 
                             alt="{{ $item['name'] }}"
                             style="max-height: 180px; object-fit: cover;">
                    </div>
                    
                    {{-- Detail produk --}}
                    <div class="col-12 col-md-8">
                        <div class="card-body d-flex flex-column h-100">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-semibold text text-center text-md-start">{{ $item['name'] }}</h5>
                                <p class="card-text text-muted mb-2 text-center text-md-start">
                                    Harga: Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                            
                                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-md-start mb-3 gap-2">
                                    <span class="text-muted">Jumlah:</span>
                                    <form action="{{ route('cart.update', ['productId' => $item['product_id']]) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" 
                                                    class="btn btn-outline-secondary {{ $item['quantity'] <= 1 ? 'disabled' : '' }}">-</button>
                                            <span class="btn btn-light">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" 
                                                    class="btn btn-outline-secondary">+</button>
                                        </div>
                                    </form>
                                </div>
                                
                                {{-- Subtotal --}}
                                <div class="text-center text-md-start mb-3">
                                    <strong class="text">Subtotal: Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            
                            {{-- Tombol hapus --}}
                            <div class="d-flex justify-content-center justify-content-md-start">
                                <form action="{{ route('cart.remove', ['productId' => $item['product_id']]) }}" method="POST" class="w-75 w-md-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Total & Checkout --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h4 class="text mb-0">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>
                    <small class="text-muted">Termasuk semua produk dalam keranjang</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('checkout.form') }}" class="btn btn-lg px-5 w-100 w-md-auto">
                        <i class="bi bi-bag-check"></i> Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- === Keranjang kosong === --}}
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-cart-x display-1 text-muted"></i>
        </div>
        <h4 class="text-muted mb-3">Keranjang Belanja Kosong</h4>
        <p class="text-muted mb-4">Yuk, temukan kebaya impian Anda dan isi keranjang belanja!</p>
        <a href="{{ url('/katalog') }}" class="btn btn-lg">
            <i class="bi bi-bag"></i> Mulai Belanja
        </a>
    </div>
@endif
@endsection