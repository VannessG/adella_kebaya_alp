@extends('layouts.app')
@section('title', 'Keranjang')
@section('content')

<h1 class="font-serif text-uppercase h3 mb-4 text-center text-md-center">Keranjang Belanja</h1>

<div class="row g-4">
    <div class="col-12 col-lg-12">
        @forelse ($cartItems as $item)
            <div class="card border rounded-0 bg-white p-3 mb-3">
                <div class="row g-3 align-items-center">
                    <div class="col-3 col-md-2">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="{{ $item['image_url'] ?? $item['image'] }}" class="object-fit-cover" alt="Img">
                        </div>
                    </div>
                    
                    <div class="col-9 col-md-10">
                        <div class="d-flex flex-column flex-md-row justify-content-between h-100">
                            <div class="mb-2 mb-md-0">
                                <h6 class="text-uppercase fw-bold mb-1 small">{{ $item['name'] }}</h6>
                                <p class="text-muted small mb-0">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <form action="{{ route('cart.update', ['productId' => $item['product_id']]) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm" style="width: 100px;">
                                        <button name="quantity" value="{{ $item['quantity'] - 1 }}" class="btn btn-outline-dark rounded-0 px-2">-</button>
                                        <span class="form-control text-center rounded-0 border-dark bg-white">{{ $item['quantity'] }}</span>
                                        <button name="quantity" value="{{ $item['quantity'] + 1 }}" class="btn btn-outline-dark rounded-0 px-2">+</button>
                                    </div>
                                </form>
                                
                                <div class="text-end">
                                    <span class="d-block fw-bold small mb-1">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    <form action="{{ route('cart.remove', ['productId' => $item['product_id']]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-link text-danger p-0 small text-decoration-none" style="font-size: 0.7rem;">HAPUS</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 border border-dashed">
                <i class="bi bi-cart-x fs-1 text-muted"></i>
                <p class="mt-3">Keranjang kosong.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-primary-custom mt-2">Belanja Sekarang</a>
            </div>
        @endforelse
    </div>

    @if(!$isEmpty)
    <div class="col-12 col-lg-4">
        <div class="card border rounded-0 bg-subtle p-4">
            <h5 class="font-serif text-uppercase mb-3 h6">Ringkasan</h5>
            <div class="d-flex justify-content-between mb-2 small">
                <span>Total Item</span>
                <strong>{{ count($cartItems) }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-4 border-top pt-3">
                <span class="fw-bold">Total Harga</span>
                <span class="fw-bold fs-5">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.form') }}" class="btn btn-primary-custom w-100 py-3 fw-bold">CHECKOUT</a>
        </div>
    </div>
    @endif
</div>
@endsection