@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start font-serif h3">Riwayat Pembelian</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    @if(count($orders) > 0)
        <div class="order-list-container shadow-sm">
            <div class="order-list-header">
                <div>Info Pesanan</div>
                <div>Total</div>
            </div>

            @foreach($orders as $order)
                <div class="order-list-item">
                    <div class="col-thumb">
                        @if($order->products->isNotEmpty())
                            <img src="{{ $order->products->first()->image_url }}" alt="Product" class="order-img">
                        @else
                            <div class="order-img bg-light d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </div>

                    <div class="col-info">
                        <span class="order-number">{{ $order->order_number }}</span>
                        <span class="order-date">{{ $order->formatted_date }}</span>
                        <div class="btn-action-group">
                            <a href="{{ url('/pesanan/' . $order->order_number) }}" class="btn-custom-outline">Detail</a>

                            @if($order->status == 'completed')
                                @foreach($order->products as $product)
                                    @if(!$order->userProductReview($product->id))
                                        <button class="btn-custom-black" data-bs-toggle="modal" data-bs-target="#revOrder{{ $order->id }}{{ $product->id }}">Ulas</button>

                                        <div class="modal fade" id="revOrder{{ $order->id }}{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-0 border-0 shadow p-4 text-start">
                                                    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        
                                                        <div class="modal-header border-0 pb-0 px-0">
                                                            <h5 class="fw-normal text-uppercase text-black w-100 text-center font-serif">Beri Ulasan</h5>
                                                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        
                                                        <div class="modal-body text-center pt-4 px-0">
                                                            <div class="mb-3">
                                                                <img src="{{ $product->image_url }}" class="border p-1" style="width: 70px; height: 70px; object-fit: cover;">
                                                                <h6 class="fw-bold mt-2 text-uppercase small">{{ $product->name }}</h6>
                                                            </div>
                                                            <div class="mb-3 text-start">
                                                                <label class="form-label small fw-bold text-muted">Rating</label>
                                                                <select name="rating" class="form-select rounded-0 bg-light border-0">
                                                                    <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                                                    <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                                    <option value="3">⭐⭐⭐ (Cukup)</option>
                                                                    <option value="2">⭐⭐ (Kurang)</option>
                                                                    <option value="1">⭐ (Kecewa)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 text-start">
                                                                <label class="form-label small fw-bold text-muted">Komentar</label>
                                                                <textarea name="comment" class="form-control rounded-0 bg-light border-0" rows="3" required></textarea>
                                                            </div>
                                                            <div class="mb-2 text-start">
                                                                <label class="form-label small fw-bold text-muted">Foto</label>
                                                                <input type="file" name="image" class="form-control rounded-0 bg-light border-0">
                                                            </div>
                                                        </div>
                                                        
                                                        <button type="submit" class="btn btn-primary-custom w-100 rounded-0 text-uppercase fw-bold">Kirim</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-total">
                        <span class="order-price">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        <span class="badge-status-custom" style="background-color: {{ $order->status_style['bg'] }}; color: {{ $order->status_style['color'] }}; border: {{ $order->status_style['border'] }};">
                            {{ $order->status_style['label'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 border" style="border-style: dashed !important; border-color: #E0E0E0 !important;">
            <i class="bi bi-bag-x fs-1 text-muted mb-3 d-block"></i>
            <h4 class="fw-normal text-uppercase text-muted mb-0" style="letter-spacing: 0.1em; font-size: 1rem;">Belum ada riwayat pembelian.</h4>
            <a href="{{ url('/katalog') }}" class="btn btn-link text-black text-uppercase mt-3 fw-bold text-decoration-none border-bottom border-black p-0 pb-1" style="font-size: 0.8rem; letter-spacing: 0.1em;">Belanja Sekarang</a>
        </div>
    @endif
</div>
@endsection