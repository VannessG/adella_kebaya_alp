@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')

@section('content')
<h1 class="fw-bold mb-4">Riwayat Penyewaan</h1>

@if(count($rents) > 0)
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">No. Sewa</th>
                            <th>Periode</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rents as $rent)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $rent->rent_number }}</td>
                            <td>
                                <div class="small fw-bold">{{ $rent->start_date->format('d M Y') }}</div>
                                <div class="text-muted small">s/d {{ $rent->end_date->format('d M Y') }}</div>
                            </td>
                            <td class="fw-bold" style="color: var(--primary-color);">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge rounded-pill 
                                    @if($rent->status == 'returned' || $rent->status == 'completed') bg-success 
                                    @elseif($rent->status == 'pending') bg-secondary
                                    @elseif($rent->status == 'cancelled') bg-dark
                                    @else bg-info @endif">
                                    {{ $statusOptions[$rent->status] ?? $rent->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('rent.show', $rent->rent_number) }}" class="btn btn-sm btn-outline-custom">Detail</a>

                                    @if($rent->status == 'completed' || $rent->status == 'returned')
                                        @foreach($rent->products as $product)
                                            {{-- Memanggil method dari Model Rent --}}
                                            @if(!$rent->userProductReview($product->id))
                                                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#revRent{{ $rent->id }}{{ $product->id }}">
                                                    Ulas Kebaya
                                                </button>

                                                {{-- Modal Review Rent --}}
                                                <div class="modal fade" id="revRent{{ $rent->id }}{{ $product->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0 shadow">
                                                            <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="rent_id" value="{{ $rent->id }}">
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="fw-bold">Beri Ulasan Sewa</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="{{ $product->image_url }}" class="rounded-3 mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                                                    <h6 class="fw-bold mb-3">{{ $product->name }}</h6>
                                                                    <div class="mb-3 text-start">
                                                                        <label class="form-label fw-semibold">Rating</label>
                                                                        <select name="rating" class="form-select border-0 bg-light">
                                                                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                                                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                                            <option value="3">⭐⭐⭐ (Cukup)</option>
                                                                            <option value="2">⭐⭐ (Kurang)</option>
                                                                            <option value="1">⭐ (Kecewa)</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3 text-start">
                                                                        <label class="form-label fw-semibold">Komentar</label>
                                                                        <textarea name="comment" class="form-control border-0 bg-light" rows="3" required placeholder="Bagaimana kondisi kebaya saat Anda sewa?"></textarea>
                                                                    </div>
                                                                    <div class="mb-3 text-start">
                                                                        <label class="form-label fw-semibold">Foto (Opsional)</label>
                                                                        <input type="file" name="image" class="form-control border-0 bg-light">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Skip</button>
                                                                    <button type="submit" class="btn btn-primary-custom px-4">Kirim Ulasan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
@else
    <div class="text-center py-5">
        <h4 class="text-muted">Belum ada riwayat penyewaan.</h4>
    </div>
@endif
@endsection