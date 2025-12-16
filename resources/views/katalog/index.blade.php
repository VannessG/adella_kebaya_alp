@extends('layouts.app')

@section('title', 'Katalog Kebaya')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold mb-2">Katalog Kebaya</h1>
        <p class="text-muted mb-0">Temukan kebaya impianmu dari koleksi terbaik kami</p>
    </div>
    <form action="{{ url('/katalog') }}" method="GET" class="mt-3 mt-md-0 position-relative" style="width: 300px;">
        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control rounded-pill ps-4 pe-5" placeholder="Cari model atau kategori...">
        <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-2 text-muted border-0 bg-transparent">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card shadow-sm p-3">
            <h5 class="fw-bold mb-3 px-2">Kategori</h5>
            <div class="list-group list-group-flush">
                <a href="{{ url('/katalog') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1 {{ !request('category') ? 'active bg-light text-dark fw-bold' : '' }}">Semua Produk</a>
                @foreach(\App\Models\Category::all() as $cat)
                    <a href="{{ url('/kategori/' . $cat->id) }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        {{ $cat->name }}
                        <span class="badge bg-secondary bg-opacity-10 text-dark float-end rounded-pill">{{ $cat->products_count ?? 0 }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative overflow-hidden rounded-top">
                            <img src="{{ $product->image_url }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}" style="height: 280px;">
                            @if($product->is_discounted)
                                <span class="position-absolute top-0 start-0 badge bg-danger m-2 rounded-pill">Promo</span>
                            @endif
                            @if(!$product->is_available)
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center text-white fw-bold">
                                    STOK HABIS
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <small class="text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ $product->category->name }}</small>
                            <h5 class="card-title fw-bold mt-1 mb-2">{{ $product->name }}</h5>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning small me-2">
                                    @for($i=0; $i<5; $i++)
                                        <i class="bi bi-star{{ $i < round($product->average_rating) ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-muted small">({{ $product->reviews_count ?? 0 }})</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <small class="text-muted d-block">Harga Beli</small>
                                    @if($product->is_discounted)
                                        <span class="text-decoration-line-through text-muted small">Rp {{ number_format($product->price, 0, ',', '.') }}</span><br>
                                        <span class="fw-bold text-dark fs-5">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="fw-bold text-dark fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($product->is_available_for_rent)
                                <div class="mt-2 pt-2 border-top">
                                    <small class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i> Tersedia Sewa: Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}/hari</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-3">
                            <a href="{{ url('/katalog/detail/' . $product->id) }}" class="btn btn-outline-custom w-100 rounded-pill">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection