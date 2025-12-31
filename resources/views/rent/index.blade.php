@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')

@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Riwayat Penyewaan</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    @if(count($rents) > 0)
        <div class="card border rounded-0 shadow-sm" style="border-color: var(--border-color);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead class="bg-subtle border-bottom border-black">
                            <tr>
                                <th class="ps-4 py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">No. Sewa</th>
                                <th class="py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Periode</th>
                                <th class="py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Total</th>
                                <th class="py-4 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Status</th>
                                <th class="pe-4 py-4 text-end text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rents as $rent)
                            <tr style="border-bottom: 1px solid #F0F0F0;">
                                <td class="ps-4 py-4 fw-bold text-black" style="font-family: 'Jost', sans-serif;">{{ $rent->rent_number }}</td>
                                <td class="py-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-black small text-uppercase" style="letter-spacing: 0.05em;">{{ $rent->start_date->format('d M Y') }}</span>
                                        <span class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.05em;">s/d {{ $rent->end_date->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 fw-bold text-black">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4">
                                    <span class="badge rounded-0 fw-normal text-uppercase px-3 py-2 small border" 
                                        style="letter-spacing: 0.05em; font-size: 0.7rem;
                                        @if($rent->status == 'completed') background-color: #000; color: #fff; border-color: #000;
                                        @elseif($rent->status == 'payment_check') background-color: #ffc107; color: #000; border-color: #ffc107; {{-- Warna Kuning untuk Pengecekan --}}
                                        @elseif($rent->status == 'pending') background-color: #fff; color: #555; border-color: #ccc;
                                        @elseif($rent->status == 'active') background-color: #fff; color: #000; border-color: #000;
                                        @elseif($rent->status == 'cancelled') background-color: #fff; color: #d9534f; border-color: #d9534f;
                                        @else background-color: #fff; color: #000; border-color: #000; @endif">
                                        @if($rent->status == 'payment_check')
                                            Pengecekan Pembayaran
                                        @elseif($rent->status == 'pending')
                                            Menunggu Pembayaran
                                        @elseif($rent->status == 'processing')
                                            Diproses
                                        @elseif($rent->status == 'shipping')
                                            Dikirim
                                        @elseif($rent->status == 'completed')
                                            Selesai
                                        @elseif($rent->status == 'cancelled')
                                            Dibatalkan
                                        @else
                                            {{ $rent->status }}
                                        @endif
                                    </span>
                                </td>
                                <td class="pe-4 py-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('rent.show', $rent->rent_number) }}" class="btn btn-outline-custom btn-sm rounded-0 px-3 text-uppercase" style="font-size: 0.7rem;">Detail</a>

                                        @if($rent->status == 'completed' || $rent->status == 'returned')
                                            @foreach($rent->products as $product)
                                                @if(!$rent->userProductReview($product->id))
                                                    <button class="btn btn-primary-custom btn-sm rounded-0 px-3 text-uppercase" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#revRent{{ $rent->id }}{{ $product->id }}">
                                                        Ulas Kebaya
                                                    </button>

                                                    {{-- Modal Review Rent (Dipindah ke dalam loop agar terhubung dengan ID yang benar, sesuai logika awal) --}}
                                                    <div class="modal fade" id="revRent{{ $rent->id }}{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded-0 border-0 shadow p-4">
                                                                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="rent_id" value="{{ $rent->id }}">
                                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                    
                                                                    <div class="modal-header border-0 pb-0">
                                                                        <h5 class="fw-normal text-uppercase text-black w-100 text-center" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Beri Ulasan Sewa</h5>
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
                                                                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                                                                <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                                                                <option value="2">⭐⭐ (Kurang)</option>
                                                                                <option value="1">⭐ (Kecewa)</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="mb-4 text-start">
                                                                            <label class="form-label small text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.1em;">Komentar</label>
                                                                            <textarea name="comment" class="form-control rounded-0 bg-subtle border-0 p-3" rows="3" required placeholder="Bagaimana kondisi kebaya saat Anda sewa?" style="font-size: 0.9rem;"></textarea>
                                                                        </div>

                                                                        <div class="mb-2 text-start">
                                                                            <label class="form-label small text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 0.1em;">Foto (Opsional)</label>
                                                                            <input type="file" name="image" class="form-control rounded-0 bg-subtle border-0" style="font-size: 0.8rem;">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="modal-footer border-0 justify-content-center pt-0 pb-2">
                                                                        <button type="button" class="btn btn-link text-muted text-decoration-none text-uppercase small me-2" data-bs-dismiss="modal" style="letter-spacing: 0.1em;">Skip</button>
                                                                        <button type="submit" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">Kirim Ulasan</button>
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
        <div class="text-center py-5 border" style="border-style: dashed !important; border-color: #E0E0E0 !important;">
            <i class="bi bi-clock-history display-4 text-muted mb-3 d-block"></i>
            <h4 class="fw-normal text-uppercase text-muted mb-0" style="letter-spacing: 0.1em; font-size: 1rem;">Belum ada riwayat penyewaan.</h4>
            <a href="{{ url('/katalog') }}" class="btn btn-link text-black text-uppercase mt-3 fw-bold text-decoration-none border-bottom border-black p-0 pb-1" style="font-size: 0.8rem; letter-spacing: 0.1em;">Mulai Menyewa</a>
        </div>
    @endif
</div>
@endsection