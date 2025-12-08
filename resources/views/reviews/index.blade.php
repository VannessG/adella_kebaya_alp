@extends('layouts.app')

@section('title', 'Review Saya')

@section('content')
<div class="container">
    <h1 class="fw-bold text mb-4">Review Saya</h1>

    @if($reviews->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-chat-left-text display-1 text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">Belum Ada Review</h4>
            <p class="text-muted mb-4">Anda belum memberikan review untuk produk yang dibeli/disewa.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $review)
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title fw-semibold mb-1">{{ $review->product->name }}</h5>
                                    <small class="text-muted">
                                        @if($review->order_id)
                                            Beli: {{ $review->order->order_number }}
                                        @else
                                            Sewa: {{ $review->rent->rent_number }}
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            
                            <p class="card-text mb-3">{{ $review->comment }}</p>
                            
                            @if($review->image_url)
                                <div class="mb-3">
                                    <img src="{{ $review->image_url }}" alt="Review Image" 
                                         class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ $review->created_at->format('d M Y') }}
                                </small>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Yakin ingin menghapus review ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection