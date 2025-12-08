@extends('layouts.app')

@section('title', 'Beri Review')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold text mb-4">Beri Review</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text">Pesanan yang Direview</h5>
                        @if($order)
                            <p class="mb-1"><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                            <p class="mb-0"><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y') }}</p>
                        @elseif($rent)
                            <p class="mb-1"><strong>No. Sewa:</strong> {{ $rent->rent_number }}</p>
                            <p class="mb-0"><strong>Periode:</strong> {{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}</p>
                        @endif
                    </div>

                    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if($order)
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                        @endif
                        
                        @if($rent)
                            <input type="hidden" name="rent_id" value="{{ $rent->id }}">
                        @endif

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Pilih Produk</label>
                            <select class="form-select @error('product_id') is-invalid @enderror" 
                                    id="product_id" name="product_id" required>
                                <option value="">Pilih Produk</option>
                                @if($order)
                                    @foreach($order->products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                @elseif($rent)
                                    @foreach($rent->products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                           class="d-none" {{ $i == 5 ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="bi bi-star fs-1"></i>
                                        <i class="bi bi-star-fill fs-1"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" name="comment" rows="4" 
                                      placeholder="Bagaimana pengalaman Anda dengan produk ini?" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Foto (Opsional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Upload foto produk yang Anda terima</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn">Kirim Review</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Nanti Saja</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.rating-input input').forEach(input => {
    input.addEventListener('change', function() {
        const value = this.value;
        console.log('Rating selected:', value);
    });
});
</script>
@endsection