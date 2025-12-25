@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<h1 class="fw-bold mb-4">Riwayat Pesanan</h1>

@if(count($orders) > 0)
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $order->order_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                            <td class="fw-bold" style="color: var(--primary-color);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'completed') bg-success 
                                    @elseif($order->status == 'pending') bg-secondary
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @else bg-warning text-dark @endif">
                                    {{ $statusOptions[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ url('/pesanan/' . $order->order_number) }}" class="btn btn-sm btn-outline-custom">Detail</a>
                                    
                                    @if($order->status == 'completed')
                                        @foreach($order->products as $product)
                                            @if(!$order->userProductReview($product->id))
                                                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#revOrder{{ $order->id }}{{ $product->id }}">
                                                    Review {{ $product->name }}
                                                </button>
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

    {{-- MODAL DILETAKKAN DI LUAR CARD/TABEL UNTUK MENCEGAH FLICKERING --}}
    @foreach($orders as $order)
        @if($order->status == 'completed')
            @foreach($order->products as $product)
                @if(!$order->userProductReview($product->id))
                    <div class="modal fade" id="revOrder{{ $order->id }}{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="modal-header border-0">
                                        <h5 class="fw-bold">Beri Ulasan Kebaya</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ $product->image_url }}" class="rounded-3 mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                        <h6 class="fw-bold mb-3">{{ $product->name }}</h6>
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold">Rating</label>
                                            <select name="rating" class="form-select border-0 bg-light">
                                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Sesuai)</option>
                                                <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                                <option value="2">⭐⭐ (Kurang)</option>
                                                <option value="1">⭐ (Buruk)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold">Komentar</label>
                                            <textarea name="comment" class="form-control border-0 bg-light" rows="3" required placeholder="Ceritakan pengalaman Anda..."></textarea>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label fw-semibold">Foto Produk (Opsional)</label>
                                            <input type="file" name="image" class="form-control border-0 bg-light">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary-custom px-4">Kirim Ulasan</button>
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
    <div class="text-center py-5">
        <h4 class="text-muted">Belum ada riwayat pesanan.</h4>
    </div>
@endif
@endsection