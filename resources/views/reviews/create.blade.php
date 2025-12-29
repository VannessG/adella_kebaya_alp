@extends('layouts.app')

@section('title', 'Beri Review')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Beri Ulasan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Bagikan pengalaman Anda</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                <div class="bg-subtle p-4 border mb-5" style="border-color: #eee;">
                    <h5 class="fw-normal text-uppercase text-black mb-3 pb-2 border-bottom border-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em; font-size: 1rem;">Detail Transaksi</h5>
                    @if($order)
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">No. Pesanan</span>
                            <span class="text-black fw-bold">{{ $order->order_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Tanggal</span>
                            <span class="text-black">{{ $order->order_date->format('d M Y') }}</span>
                        </div>
                    @elseif($rent)
                        <div class="d-flex justify-content-between mb-2 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">No. Sewa</span>
                            <span class="text-black fw-bold">{{ $rent->rent_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0 small text-uppercase" style="letter-spacing: 0.05em;">
                            <span class="text-muted">Periode</span>
                            <span class="text-black">{{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date->format('d M Y') }}</span>
                        </div>
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

                    <div class="mb-4">
                        <label for="product_id" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pilih Produk</label>
                        <select class="form-select rounded-0 bg-subtle border-0 p-3 @error('product_id') is-invalid @enderror" 
                                id="product_id" name="product_id" required>
                            <option value="">-- PILIH PRODUK --</option>
                            @if($order)
                                @foreach($order->products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            @elseif($rent)
                                @foreach($rent->products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted d-block" style="letter-spacing: 0.1em; font-size: 0.7rem;">Rating Anda</label>
                        <div class="d-flex gap-2 align-items-center star-rating-container">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" class="btn-check rating-radio" name="rating" id="star{{ $i }}" value="{{ $i }}" 
                                    {{ old('rating') == $i ? 'checked' : '' }} required>
                                
                                <label class="star-label cursor-pointer transition-all" for="star{{ $i }}" data-value="{{ $i }}">
                                    <i class="bi bi-star fs-3 text-muted star-icon-empty"></i>
                                    <i class="bi bi-star-fill fs-3 text-black star-icon-fill d-none"></i>
                                </label>
                            @endfor
                        </div>
                        
                        <div class="form-text small mt-2" id="rating-text">Klik bintang untuk memberi nilai.</div>

                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="comment" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Komentar</label>
                        <textarea class="form-control rounded-0 bg-subtle border-0 p-3 @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4" placeholder="Ceritakan pengalaman Anda menggunakan produk ini..." required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="image" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Foto (Opsional)</label>
                        <input type="file" class="form-control rounded-0 bg-subtle border-0 p-3 @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <div class="form-text small text-muted fst-italic mt-2">Format: JPG, PNG. Maksimal 2MB.</div>
                        @error('image')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-3 d-md-flex">
                        <button type="submit" class="btn btn-primary-custom rounded-0 py-3 px-5 text-uppercase fw-bold flex-grow-1" style="font-size: 0.8rem; letter-spacing: 0.1em;">Kirim Ulasan</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-custom rounded-0 py-3 px-5 text-uppercase fw-bold flex-grow-1" style="font-size: 0.8rem; letter-spacing: 0.1em;">Nanti Saja</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('.rating-radio');
    const starLabels = document.querySelectorAll('.star-label');
    const ratingText = document.getElementById('rating-text');
    
    // Teks keterangan rating (Opsional)
    const ratingDescriptions = {
        1: 'Sangat Buruk',
        2: 'Kurang',
        3: 'Cukup',
        4: 'Bagus',
        5: 'Sempurna'
    };

    function updateStars(value) {
        starLabels.forEach((label, index) => {
            const emptyIcon = label.querySelector('.star-icon-empty');
            const fillIcon = label.querySelector('.star-icon-fill');
            const starValue = index + 1;

            if (starValue <= value) {
                // Bintang ini harus AKTIF (Hitam Solid)
                emptyIcon.classList.add('d-none');
                fillIcon.classList.remove('d-none');
            } else {
                // Bintang ini harus MATI (Abu-abu Outline)
                emptyIcon.classList.remove('d-none');
                fillIcon.classList.add('d-none');
            }
        });

        // Update teks keterangan jika ada
        if(value > 0 && ratingDescriptions[value]) {
            ratingText.textContent = ratingDescriptions[value];
            ratingText.classList.add('text-black', 'fw-bold');
        }
    }

    // Event Listener saat user mengklik bintang (radio button berubah)
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            updateStars(this.value);
        });
    });

    // Cek jika ada old input (misal validasi gagal), kembalikan status bintang
    const checkedRadio = document.querySelector('.rating-radio:checked');
    if (checkedRadio) {
        updateStars(checkedRadio.value);
    }
});
</script>
@endsection