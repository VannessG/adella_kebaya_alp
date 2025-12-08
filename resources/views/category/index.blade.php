@extends('layouts.app')

@section('title', 'Kategori Kebaya')

@section('content')
<h1 class="fw-bold text mb-4">Kategori Kebaya</h1>

<div class="row g-4">
    @foreach ($categories as $category)
        <div class="col-md-6 col-lg-4">
            <div class="card text-center border-0 shadow-sm hover-shadow">
                <div class="card-body py-5">
                    <h5 class="card-title text fw-semibold">{{ $category['name'] }}</h5>
                    <p class="card-text text-muted small">{{ $category['description'] }}</p>
                    <a href="{{ url('/kategori/' . $category->id) }}" class="btn btn-sm">Lihat Produk</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection