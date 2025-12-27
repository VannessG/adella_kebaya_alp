@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="row g-5">
    {{-- Product Image --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden rounded-4">
            <img src="{{ $product->image_url }}" class="img-fluid w-100" alt="{{ $product->name }}" style="max-height: 600px; object-fit: cover;">
        </div>
    </div>

    {{-- Product Details --}}
    <div class="col-lg-6">
        <div class="h-100 d-flex flex-column">
            <div class="mb-1">
                <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 rounded-pill">{{ $product->category->name }}</span>
            </div>
            <h1 class="fw-bold display-5 mb-2 mt-2">{{ $product->name }}</h1>
            
            {{-- Rating --}}
            <div class="d-flex align-items-center mb-4">
                <div class="text-warning me-2">
                    @for($i=1; $i<=5; $i++)
                        <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small border-start ps-2 ms-2">{{ $product->reviews->count() }} Ulasan</span>
            </div>

            {{-- Price Section --}}
            <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                <div class="row">
                    <div class="col-6 border-end">
                        <small class="text-muted text-uppercase fw-bold">Harga Beli</small>
                        <div class="mt-1">
                            @if($product->is_discounted)
                                <div class="text-decoration-line-through text-muted small">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="fs-3 fw-bold text-danger">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                            @else
                                <div class="fs-3 fw-bold" style="color: var(--primary-color);">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 ps-4">
                        <small class="text-muted text-uppercase fw-bold">Harga Sewa</small>
                        @if($product->is_available_for_rent)
                            <div class="mt-1">
                                <div class="fs-3 fw-bold text-success">Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}<span class="fs-6 text-muted fw-normal">/hari</span></div>
                                <small class="text-muted">Min. {{ $product->min_rent_days }} hari</small>
                            </div>
                        @else
                            <div class="mt-1 text-muted fst-italic">Tidak disewakan</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <h5 class="fw-bold">Deskripsi</h5>
                <p class="text-muted" style="line-height: 1.8;">{{ $product->description }}</p>
                <ul class="list-unstyled text-muted small mt-2">
                    <li><i class="bi bi-check2-circle text-success me-2"></i>Stok Tersedia: <strong>{{ $product->stock }}</strong> pcs</li>
                    <li><i class="bi bi-check2-circle text-success me-2"></i>Berat: {{ $product->weight ?? 0 }} gram</li>
                    <li><i class="bi bi-geo-alt me-2"></i>Lokasi: Cabang {{ $product->branch->name ?? '-' }}</li>
                </ul>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-auto d-grid gap-2 d-md-flex">
                @auth
                    @if(auth()->user()->role === 'user')
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-custom w-100 py-3 rounded-pill fw-bold">
                                    <i class="bi bi-cart-plus me-2"></i> Keranjang
                                </button>
                            </form>
                            <a href="{{ route('checkout.form') }}?product={{ $product->id }}&quantity=1" class="btn btn-primary-custom flex-grow-1 py-3 rounded-pill fw-bold">
                                Beli Sekarang
                            </a>
                            
                            @if($product->is_available_for_rent)
                                <a href="{{ route('rent.create', $product->id) }}" class="btn btn-success flex-grow-1 py-3 rounded-pill fw-bold text-white">
                                    Sewa Sekarang
                                </a>
                            @endif
                        @else
                            <button class="btn btn-secondary w-100 py-3 rounded-pill" disabled>Stok Habis</button>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-custom flex-grow-1 py-3 rounded-pill fw-bold">Masuk untuk Membeli</a>
                @endauth
            </div> 
        </div>
    </div>
</div>

<div class="row mt-5 pt-5 border-top">
    <div class="col-12">
        <h3 class="fw-bold mb-4">Ulasan Pelanggan</h3>
        
        @php
            $approvedReviews = $product->reviews->where('is_approved', true);
        @endphp

        @if($approvedReviews->count() > 0)
            <div class="row g-4">
                @foreach($approvedReviews as $review)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3" 
                                     style="width: 45px; height: 45px; background-color: var(--primary-color); color: white;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $review->user->name }}</h6>
                                    <div class="text-warning small">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted ms-auto">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-muted mb-0" style="line-height: 1.6;">"{{ $review->comment }}"</p>
                            
                            @if($review->image)
                                <img src="{{ asset('storage/' . $review->image) }}" 
                                     class="mt-3 rounded-4 shadow-sm" 
                                     style="height: 120px; width: 120px; object-fit: cover; cursor: pointer;"
                                     data-bs-toggle="modal" data-bs-target="#imgModal{{ $review->id }}">
                                
                                {{-- Lightbox Modal --}}
                                <div class="modal fade" id="imgModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-transparent border-0">
                                            <img src="{{ asset('storage/' . $review->image) }}" class="img-fluid rounded-4">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 rounded-4" style="background: #fff; border: 2px dashed #eee;">
                <i class="bi bi-chat-left-dots display-4 text-muted mb-3 d-block"></i>
                <p class="text-muted mb-0">Belum ada ulasan untuk kebaya ini. Jadilah yang pertama memberikan ulasan!</p>
            </div>
        @endif
    </div>
</div>
@endsection