@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Keranjang Belanja</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    @if (!$isEmpty)
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">
                    @foreach ($cartItems as $item)
                        <div class="card border rounded-0 bg-white p-3" style="border-color: var(--border-color);">
                            <div class="row g-0 align-items-center">
                                <div class="col-4 col-md-3">
                                    <div class="ratio ratio-1x1 bg-subtle overflow-hidden border" style="border-color: #F0F0F0 !important;">
                                        <img src="{{ $item['image_url'] ?? $item['image'] }}" class="object-fit-cover" alt="{{ $item['name'] }}">
                                    </div>
                                </div>
                                
                                <div class="col-8 col-md-9 ps-3 ps-md-4">
                                    <div class="d-flex flex-column h-100 justify-content-between">
                                        <div class="mb-3">
                                            <h5 class="card-title fw-normal text-uppercase text-black mb-1" style="font-family: 'Marcellus', serif; letter-spacing: 0.05em; font-size: 1rem;">
                                                {{ $item['name'] }}
                                            </h5>
                                            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                                                Harga: <span class="text-black fw-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                    
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <form action="{{ route('cart.update', ['productId' => $item['product_id']]) }}" method="POST">
                                                @csrf
                                                <div class="btn-group border border-black rounded-0" role="group">
                                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" 
                                                            class="btn btn-link text-black text-decoration-none rounded-0 px-2 py-1 {{ $item['quantity'] <= 1 ? 'disabled opacity-50' : '' }}" 
                                                            style="font-size: 0.8rem;">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <span class="btn btn-link text-black text-decoration-none fw-bold px-3 py-1 cursor-default" style="font-size: 0.9rem;">
                                                        {{ $item['quantity'] }}
                                                    </span>
                                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" 
                                                            class="btn btn-link text-black text-decoration-none rounded-0 px-2 py-1"
                                                            style="font-size: 0.8rem;">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </form>

                                            <div class="d-flex align-items-center gap-3 ms-auto">
                                                <div class="text-end d-none d-sm-block">
                                                    <small class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 0.1em;">Subtotal</small>
                                                    <span class="fw-bold text-black">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                                </div>
                                                <form action="{{ route('cart.remove', ['productId' => $item['product_id']]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none" title="Hapus Item">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="d-block d-sm-none mt-2 pt-2 border-top" style="border-color: #eee !important;">
                                            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Subtotal:</small> 
                                            <strong class="text-black">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border rounded-0 bg-subtle p-4 sticky-top" style="border-color: var(--border-color); top: 100px; z-index: 1;">
                    <h5 class="fw-normal text-uppercase text-black mb-4 pb-3 border-bottom border-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small text-uppercase" style="letter-spacing: 0.05em;">Total Produk</span>
                        <span class="text-black fw-bold">{{ count($cartItems) }} Item</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted small text-uppercase" style="letter-spacing: 0.05em;">Total Harga</span>
                        <span class="text-black fw-bold fs-5" style="font-family: 'Marcellus', serif;">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('checkout.form') }}" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold" style="letter-spacing: 0.15em; font-size: 0.8rem;">
                            Lanjutkan Pembelian
                        </a>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <a href="{{ url('/katalog') }}" class="text-decoration-none text-muted small text-uppercase hover-underline" style="letter-spacing: 0.1em; font-size: 0.7rem;">
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-6 text-center py-5">
                <div class="mb-4 text-muted opacity-25">
                    <i class="bi bi-bag-x display-1"></i>
                </div>
                <h4 class="fw-normal text-uppercase text-black mb-3" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Keranjang Kosong</h4>
                <p class="text-muted mb-5 fw-light" style="letter-spacing: 0.05em;">Belum ada item yang ditambahkan. Mari temukan koleksi terbaik untuk Anda.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-primary-custom rounded-0 px-5 py-3 text-uppercase fw-bold" style="letter-spacing: 0.15em; font-size: 0.8rem;">Mulai Belanja</a>
            </div>
        </div>
    @endif
</div>
@endsection