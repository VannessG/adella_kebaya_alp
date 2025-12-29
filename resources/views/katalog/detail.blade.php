@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="row g-5">
    <div class="col-lg-6">
        <div class="card border rounded-0 p-3 bg-white" style="border-color: var(--border-color);">
            <div class="ratio ratio-1x1 bg-subtle overflow-hidden">
                <img src="{{ $product->image_url }}" class="object-fit-cover" alt="{{ $product->name }}" style="transition: transform 0.5s ease;">
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="h-100 d-flex flex-column ps-lg-4">
            <div class="mb-4 pb-4 border-bottom" style="border-color: var(--border-color) !important;">
                <span class="d-inline-block small text-uppercase fw-bold text-muted mb-2" style="letter-spacing: 0.15em; font-size: 0.7rem;">{{ $product->category->name }}</span>
                <h1 class="fw-normal display-5 text-black text-uppercase" style="font-family: 'Marcellus', serif; letter-spacing: 0.05em;">{{ $product->name }}</h1>
                <div class="d-flex align-items-center mt-3">
                    <div class="text-black me-3" style="font-size: 0.9rem;">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="small text-muted text-uppercase" style="letter-spacing: 0.05em; font-size: 0.7rem;">{{ $product->reviews->count() }} Ulasan</span>
                </div>
            </div>

            <div class="mb-5">
                <div class="row">
                    <div class="col-6">
                        <small class="d-block text-uppercase fw-bold text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.1em;">Harga Beli</small>
                        @if($product->is_discounted)
                            <div class="text-decoration-line-through text-muted small mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="fs-3 fw-normal text-black" style="font-family: 'Marcellus', serif;">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                        @else
                            <div class="fs-3 fw-normal text-black" style="font-family: 'Marcellus', serif;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                    
                    <div class="col-6 border-start ps-4" style="border-color: var(--border-color) !important;">
                        <small class="d-block text-uppercase fw-bold text-muted mb-2" style="font-size: 0.7rem; letter-spacing: 0.1em;">Harga Sewa</small>
                        @if($product->is_available_for_rent)
                            <div class="fs-3 fw-normal text-black" style="font-family: 'Marcellus', serif;">Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}<span class="fs-6 text-muted ms-1">/hari</span></div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Min. {{ $product->min_rent_days }} hari</small>
                        @else
                            <div class="fs-5 text-muted fst-italic mt-2">Tidak tersedia</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <h5 class="fw-bold text-uppercase text-black mb-3" style="font-size: 0.85rem; letter-spacing: 0.1em;">Detail Produk</h5>
                <p class="text-muted small" style="line-height: 1.8; text-align: justify;">{{ $product->description }}</p>
                <div class="d-flex flex-wrap gap-4 mt-4 pt-3 border-top" style="border-color: var(--border-color) !important;">
                    <div class="d-flex align-items-center small text-uppercase" style="letter-spacing: 0.05em;">
                        <i class="bi bi-box-seam me-2 text-black"></i> Stok: <span class="fw-bold ms-1">{{ $product->stock }}</span>
                    </div>
                    <div class="d-flex align-items-center small text-uppercase" style="letter-spacing: 0.05em;">
                        <i class="bi bi-geo-alt me-2 text-black"></i> Lokasi: <span class="fw-bold ms-1">{{ $product->branch->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-4">
                @auth
                    @if(auth()->user()->role === 'user')
                        @if($product->stock > 0)
                            <div class="d-grid gap-3 d-md-flex">
                                @if($product->is_available)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-outline-custom w-100 py-3 text-uppercase fw-bold rounded-0" style="letter-spacing: 0.15em; font-size: 0.75rem;">Tambah Keranjang</button>
                                    </form>
                                    <a href="{{ route('checkout.form') }}?product={{ $product->id }}&quantity=1" class="btn btn-primary-custom flex-grow-1 py-3 text-uppercase fw-bold rounded-0" style="letter-spacing: 0.15em; font-size: 0.75rem;">Beli</a>
                                @endif
                                @if($product->is_available_for_rent)
                                    <a href="{{ route('rent.create', $product->id) }}" class="btn btn-primary-custom flex-grow-1 py-3 text-uppercase fw-bold rounded-0" style="letter-spacing: 0.15em; font-size: 0.75rem; background-color: #333; border-color: #333;">Sewa</a>
                                @endif
                            </div>
                        @else
                            <button class="btn btn-outline-secondary w-100 py-3 rounded-0 text-uppercase" disabled style="letter-spacing: 0.15em;">Stok Habis</button>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary-custom w-100 py-3 text-uppercase fw-bold rounded-0" style="letter-spacing: 0.15em; font-size: 0.8rem;">Masuk untuk Membeli / Menyewa</a>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 pt-5 border-top" style="border-color: var(--border-color) !important;">
    <div class="col-12">
        <h3 class="fw-normal text-uppercase text-black mb-5" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Ulasan Pelanggan</h3>
        @if($approvedReviews->count() > 0)
            <div class="row g-4">
                @foreach($approvedReviews as $review)
                    <div class="col-md-6">
                        <div class="card h-100 border rounded-0 p-4 bg-white" style="border-color: var(--border-color);">
                            <div class="d-flex align-items-center mb-4">
                                <div class="d-flex align-items-center justify-content-center me-3 border border-black bg-black text-white" style="width: 40px; height: 40px; font-family: 'Marcellus', serif;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold text-uppercase mb-0 text-black" style="font-size: 0.8rem; letter-spacing: 0.05em;">{{ $review->user->name }}</h6>
                                    <div class="text-black" style="font-size: 0.7rem;">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted ms-auto text-uppercase" style="font-size: 0.65rem;">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="text-muted mb-0 small fst-italic" style="line-height: 1.6;">"{{ $review->comment }}"</p>
                            @if($review->image)
                                <img src="{{ asset('storage/' . $review->image) }}" class="mt-3 border cursor-pointer" style="height: 100px; width: 100px; object-fit: cover; border-color: var(--border-color);"data-bs-toggle="modal" data-bs-target="#imgModal{{ $review->id }}">
                                <div class="modal fade" id="imgModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-transparent border-0">
                                            <div class="modal-body p-0 text-center">
                                                <img src="{{ asset('storage/' . $review->image) }}" class="img-fluid border border-white p-1 bg-white">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-5 text-center border" style="border-style: dashed !important; border-color: var(--border-color) !important;">
                <p class="text-muted mb-0 text-uppercase small" style="letter-spacing: 0.1em;">Belum ada ulasan untuk produk ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection