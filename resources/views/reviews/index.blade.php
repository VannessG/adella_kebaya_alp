@extends('layouts.app')

@section('title', 'Review Saya')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Review Saya</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Riwayat ulasan produk Anda</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    @if($reviews->isEmpty())
        <div class="text-center py-5 border" style="border-style: dashed !important; border-color: #E0E0E0 !important;">
            <i class="bi bi-chat-left-quote display-4 text-muted mb-3 d-block opacity-25"></i>
            <h4 class="fw-normal text-uppercase text-black mb-2" style="letter-spacing: 0.1em;">Belum Ada Review</h4>
            <p class="text-muted small mb-4">Anda belum memberikan review untuk produk yang dibeli/disewa.</p>
            <a href="{{ url('/katalog') }}" class="btn btn-outline-custom rounded-0 text-uppercase fw-bold px-4 py-2" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $review)
                <div class="col-md-6">
                    <div class="card h-100 border rounded-0 bg-white p-4" style="border-color: var(--border-color);">
                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3" style="border-color: #eee !important;">
                            <div>
                                <h5 class="fw-bold text-uppercase text-black mb-1 small" style="letter-spacing: 0.05em;">{{ $review->product->name }}</h5>
                                <div class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                    @if($review->order_id)
                                        Beli: #{{ $review->order->order_number }}
                                    @else
                                        Sewa: #{{ $review->rent->rent_number }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-black">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="bi bi-star-fill small"></i>
                                    @else
                                        <i class="bi bi-star small text-muted opacity-50"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <p class="card-text text-muted small fst-italic mb-3" style="line-height: 1.6;">"{{ $review->comment }}"</p>
                            
                            @if($review->image_url)
                                <div class="mb-3">
                                    <img src="{{ $review->image_url }}" alt="Review Image" class="img-fluid border p-1 bg-white cursor-pointer" style="height: 80px; width: 80px; object-fit: cover; border-color: #eee;" data-bs-toggle="modal" data-bs-target="#imgModal{{ $review->id }}">
                                    <div class="modal fade" id="imgModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-transparent border-0">
                                                <div class="modal-body p-0 text-center">
                                                    <img src="{{ $review->image_url }}" class="img-fluid border border-white p-1 bg-white">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: #eee !important;">
                            <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">{{ $review->created_at->format('d M Y') }}</small>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 text-uppercase fw-bold" 
                                        style="font-size: 0.65rem; letter-spacing: 0.1em;"
                                        onclick="return confirm('Yakin ingin menghapus review ini?')">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection