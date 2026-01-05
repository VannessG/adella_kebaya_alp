@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="row g-4 g-lg-5">
    <div class="col-12 col-md-6">
        <div class="card border rounded-0 p-0 bg-white">
            <div class="ratio ratio-1x1 bg-subtle overflow-hidden">
                <img src="{{ $product->image_url }}" class="object-fit-cover" alt="{{ $product->name }}">
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="h-100 d-flex flex-column">
            <div class="mb-4 pb-3 border-bottom">
                <span class="d-inline-block small text-uppercase fw-bold text-muted mb-2">{{ $product->category->name }}</span>
                <h1 class="fw-normal display-6 text-black text-uppercase font-serif mb-3">{{ $product->name }}</h1>
                <div class="d-flex align-items-center">
                    <div class="text-black me-3 small">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="small text-muted text-uppercase">{{ $product->reviews->count() }} Ulasan</span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <small class="d-block text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem;">Harga Beli</small>
                    <div class="fs-4 fw-normal text-black font-serif">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
                <div class="col-6 border-start ps-4">
                    <small class="d-block text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem;">Sewa / Hari</small>
                    @if($product->is_available_for_rent)
                        <div class="fs-4 fw-normal text-black font-serif">Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}</div>
                    @else
                        <span class="text-muted small">Tidak tersedia</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold text-uppercase text-black mb-2 small">Deskripsi</h5>
                <p class="text-muted small" style="line-height: 1.8;">{{ $product->description }}</p>
                <div class="d-flex flex-wrap gap-3 mt-3 small text-uppercase">
                    <span><i class="bi bi-box-seam me-1"></i> Stok: <strong>{{ $product->stock }}</strong></span>
                    <span><i class="bi bi-geo-alt me-1"></i> {{ $product->branch->name ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-auto mx-auto w-100" style="max-width: 200px;">
                @auth
                    @if(auth()->user()->role === 'user' && $product->stock > 0)
                        <div class="d-grid gap-2">
                            @if($product->is_available)
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-outline-custom w-100 py-3 fw-bold">+ KERANJANG</button>
                                </form>
                                <a href="{{ route('checkout.form') }}?product={{ $product->id }}&quantity=1" class="btn btn-primary-custom w-100 py-3 fw-bold">BELI</a>
                            @endif
                            @if($product->is_available_for_rent)
                                <a href="{{ route('rent.create', $product->id) }}" class="btn btn-black w-100 py-3 fw-bold">SEWA</a>
                            @endif
                        </div>
                    @elseif($product->stock <= 0)
                        <button class="btn btn-secondary w-100 py-3" disabled>STOK HABIS</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary-custom py-3 fw-bold">LOGIN UNTUK MEMBELI</a>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 pt-5 border-top">
    <div class="col-12">
        <h3 class="font-serif text-uppercase mb-4 h4">Ulasan Pelanggan</h3>
        @if($approvedReviews->count() > 0)
            <div class="row g-3">
                @foreach($approvedReviews as $review)
                    <div class="col-12 col-md-6">
                        <div class="card border rounded-0 p-3 bg-white h-100">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-black text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">{{ substr($review->user->name, 0, 1) }}</div>
                                <div>
                                    <h6 class="mb-0 small fw-bold text-uppercase">{{ $review->user->name }}</h6>
                                    <div class="small text-warning">
                                        @for($i=1; $i<=5; $i++) 
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i> 
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted small fst-italic mb-0">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted small">Belum ada ulasan.</p>
        @endif
    </div>
</div>
@endsection