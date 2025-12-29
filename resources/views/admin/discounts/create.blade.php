@extends('layouts.app')

@section('title', 'Tambah Diskon')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Tambah Diskon</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Buat kode promo atau potongan harga baru</p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                
                <form action="{{ route('admin.discounts.store') }}" method="POST">
                    @csrf

                    {{-- Nama Diskon --}}
                    <div class="mb-4">
                        <label for="name" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Nama Diskon</label>
                        <input type="text" class="form-control rounded-0 bg-subtle border-0 p-3 @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Promo Akhir Tahun">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kode Diskon --}}
                    <div class="mb-4">
                        <label for="code" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Kode Diskon (Opsional)</label>
                        <input type="text" class="form-control rounded-0 bg-subtle border-0 p-3 @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: SALE2024">
                        <div class="form-text small text-muted fst-italic mt-1" style="font-size: 0.75rem;">Kosongkan untuk diskon otomatis berlaku tanpa kode.</div>
                        @error('code')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tipe & Nilai --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="type" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Tipe Diskon</label>
                            <select class="form-select rounded-0 bg-subtle border-0 p-3 @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Nilai Diskon</label>
                            <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" min="0" step="0.01" required placeholder="0">
                            <div class="form-text small text-muted mt-1" id="amountHelp" style="font-size: 0.75rem;">
                                Masukkan nilai diskon
                            </div>
                            @error('amount')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Maksimal Penggunaan --}}
                    <div class="mb-4">
                        <label for="max_usage" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Maksimal Penggunaan</label>
                        <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('max_usage') is-invalid @enderror" 
                               id="max_usage" name="max_usage" value="{{ old('max_usage') }}" min="1" placeholder="Tidak Terbatas">
                        <div class="form-text small text-muted fst-italic mt-1" style="font-size: 0.75rem;">Kosongkan jika tidak ada batasan jumlah pemakaian.</div>
                        @error('max_usage')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Mulai & Akhir --}}
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Tanggal Mulai</label>
                            <input type="date" class="form-control rounded-0 bg-subtle border-0 p-3 @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.9rem;">Tanggal Berakhir</label>
                            <input type="date" class="form-control rounded-0 bg-subtle border-0 p-3 @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Checkbox Aktif --}}
                    <div class="mb-5 p-3 border bg-subtle d-flex align-items-center" style="border-color: #eee !important;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input rounded-0 border-black" id="is_active" 
                                   name="is_active" value="1" checked>
                            <label class="form-check-label text-uppercase fw-bold ms-2" for="is_active" style="font-size: 0.8rem; letter-spacing: 0.05em; padding-top: 2px;">
                                Aktifkan Diskon Ini
                            </label>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-3 text-uppercase fw-bold flex-grow-1 flex-md-grow-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Simpan Diskon
                        </button>
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-custom rounded-0 px-4 py-3 text-uppercase fw-bold flex-grow-1 flex-md-grow-0" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('type').addEventListener('change', function() {
        const amountHelp = document.getElementById('amountHelp');
        if (this.value === 'percentage') {
            amountHelp.textContent = 'Masukkan persentase diskon (0-100%)';
        } else {
            amountHelp.textContent = 'Masukkan nominal diskon (Rp)';
        }
    });
</script>
@endsection