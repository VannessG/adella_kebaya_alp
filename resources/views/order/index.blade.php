@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Riwayat Pesanan</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    @if(count($orders) > 0)
        <div class="card border rounded-0 shadow-sm" style="border-color: var(--border-color);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead class="bg-subtle border-bottom border-black">
                            <tr>
                                <th class="ps-4 py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">No. Pesanan</th>
                                <th class="py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Tanggal</th>
                                <th class="py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Total</th>
                                <th class="py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Status</th>
                                <th class="pe-4 py-4 text-end text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid #F0F0F0;">
                                <td class="ps-4 py-4 fw-bold text-black" style="font-family: 'Jost', sans-serif;">{{ $order->order_number }}</td>
                                <td class="py-4 text-muted small">{{ $order->formatted_date }}</td>
                                <td class="py-4 fw-bold text-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4">
                                    <span class="badge rounded-0 fw-normal text-uppercase px-3 py-2 small border" 
                                          style="letter-spacing: 0.05em; font-size: 0.7rem;
                                          @if($order->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                          @elseif($order->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                          @elseif($order->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                                          @else background-color: #fff; color: #000; border-color: #000; @endif">
                                        {{ $statusOptions[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="pe-4 py-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ url('/pesanan/' . $order->order_number) }}" class="btn btn-outline-custom btn-sm rounded-0 px-3 text-uppercase" style="font-size: 0.7rem;">Detail</a>
                                        @if($order->status == 'completed')
                                            @foreach($order->products as $product)
                                                @if(!$order->userProductReview($product->id))
                                                    <button class="btn btn-primary-custom btn-sm rounded-0 px-3 text-uppercase" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#revOrder{{ $order->id }}{{ $product->id }}">Review {{ $product->short_name }}</button>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @foreach($orders as $order)
            @if($order->status == 'completed')
                @foreach($order->products as $product)
                    @if(!$order->userProductReview($product->id))
                        <div class="modal fade" id="revOrder{{ $order->id }}{{ $product->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-0 border-0 shadow p-4">
                                    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-normal text-uppercase text-black w-100 text-center" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Beri Ulasan</h5>
                                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <div class="modal-body text-center pt-4">
                                            <div class="mb-4">
                                                <img src="{{ $product->image_url }}" class="border p-1" style="width: 80px; height: 80px; object-fit: cover; border-color: #eee;">
                                                <h6 class="fw-bold mt-2 text-uppercase small text-muted" style="letter-spacing: 0.05em;">{{ $product->name }}</h6>
                                            </div>

                                            <div class="mb-4 text-start">
                                                <label class="form-label small text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.1em;">Rating</label>
                                                <select name="rating" class="form-select rounded-0 bg-subtle border-0 ps-3" style="font-size: 0.9rem;">
                                                    <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                                                    <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                    <option value="3">⭐⭐⭐ (Cukup)</option>
                                                    <option value="2">⭐⭐ (Kurang)</option>
                                                    <option value="1">⭐ (Buruk)</option>
                                                </select>
                                            </div>

                                            <div class="mb-4 text-start">
                                                <label class="form-label small text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.1em;">Komentar</label>
                                                <textarea name="comment" class="form-control rounded-0 bg-subtle border-0 p-3" rows="3" required placeholder="Bagaimana pengalaman Anda mengenakan kebaya ini?" style="font-size: 0.9rem;"></textarea>
                                            </div>

                                            <div class="mb-2 text-start">
                                                <label class="form-label small text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.1em;">Foto (Opsional)</label>
                                                <input type="file" name="image" class="form-control rounded-0 bg-subtle border-0" style="font-size: 0.8rem;">
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer border-0 justify-content-center pt-0 pb-2">
                                            <button type="submit" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">Kirim Ulasan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    @else
        <div class="text-center py-5 border" style="border-style: dashed !important; border-color: #E0E0E0 !important;">
            <i class="bi bi-receipt display-4 text-muted mb-3 d-block"></i>
            <h4 class="fw-normal text-uppercase text-muted mb-0" style="letter-spacing: 0.1em; font-size: 1rem;">Belum ada riwayat pesanan.</h4>
            <a href="{{ url('/katalog') }}" class="btn btn-link text-black text-uppercase mt-3 fw-bold text-decoration-none border-bottom border-black p-0 pb-1" style="font-size: 0.8rem; letter-spacing: 0.1em;">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection