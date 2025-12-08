@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <h1 class="fw-bold text mb-4">{{ $category->name }}</h1>
    <p class="text-muted mb-4">{{ $category->description }}</p>

    <div class="row g-4">
        @foreach ($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 hover-shadow border-0">
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}"
                        style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold">{{ $product->name }}</h5>
                        <p class="card-text text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <a href="{{ url('/katalog/detail/' . $product->id) }}" class="text text-decoration-none fw-semibold">Detail</a>
                            @auth
                                @if (auth()->user()->role === 'user')
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm">+ Keranjang</button>
                                    </form>
                                @endif
                            @endauth
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-sm">+ Keranjang</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        <a href="{{ url('/kategori') }}" class="btn btn-outline-secondary">Kembali ke Kategori</a>
    </div>
@endsection