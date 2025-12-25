@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/katalog') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h1 class="fw-bold text mb-2">{{ $category->name }}</h1>
        <p class="text-muted">Menampilkan koleksi di cabang <strong>{{ session('selected_branch')->name }}</strong></p>
        @if($category->description)
            <p class="text-muted">{{ $category->description }}</p>
        @endif
    </div>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 hover-shadow border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="position-relative">
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}"
                            style="height: 250px; object-fit: cover;">
                        @if($product->stock <= 0)
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
                                <span class="text-white fw-bold">STOK HABIS</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title fw-bold mb-1">{{ $product->name }}</h5>
                        <p class="text-primary fw-bold mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        
                        <div class="mt-auto d-grid gap-2">
                            <a href="{{ route('katalog.show', $product->id) }}" class="btn btn-outline-dark rounded-pill btn-sm"> Detail</a>
                            
                            @auth
                                @if (auth()->user()->role === 'user' && $product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary-custom w-100 rounded-pill btn-sm">+ Keranjang</button>
                                    </form>
                                @endif
                            @endauth
                            
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-primary-custom rounded-pill btn-sm">+ Keranjang</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-bag-x display-1 text-muted"></i>
                <p class="mt-3 text-muted">Maaf, koleksi untuk kategori ini belum tersedia di cabang {{ session('selected_branch')->name }}.</p>
                <a href="{{ url('/katalog') }}" class="btn btn-primary rounded-pill">Lihat Katalog Lainnya</a>
            </div>
        @endforelse
    </div>

    <div class="mt-5 border-top pt-4">
        <a href="{{ url('/kategori') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Semua Kategori
        </a>
    </div>
@endsection