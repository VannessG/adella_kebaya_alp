@extends('layouts.app')

@section('title', 'Edit Diskon')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Perbarui Diskon</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Perbarui informasi diskon</p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="row justify-content-center">

        <div class="col-12 col-lg-8">
            <div class="card border rounded-0 bg-white p-4 p-md-5 shadow-sm" style="border-color: var(--border-color);">
                
                <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Diskon</label>
                        <input type="text" class="form-control rounded-0 bg-subtle border-0 p-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $discount->name) }}" required placeholder="Contoh: Diskon Kemerdekaan">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kode Diskon (Opsional)</label>
                        <input type="text" class="form-control rounded-0 bg-subtle border-0 p-3 @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $discount->code) }}" placeholder="Contoh: MERDEKA45">
                        <div class="form-text small text-muted fst-italic mt-1" style="font-size: 0.75rem;">Kosongkan untuk diskon otomatis berlaku tanpa kode.</div>
                        @error('code')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="type" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tipe Diskon</label>
                            <select class="form-select rounded-0 bg-subtle border-0 p-3 @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="amount" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nilai Diskon</label>
                            <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $discount->amount) }}" min="0" step="0.01" required placeholder="0">
                            <div class="form-text small text-muted mt-1" id="amountHelp" style="font-size: 0.75rem;">
                                {{ $discount->type === 'percentage' ? 'Masukkan persentase diskon (0-100%)' : 'Masukkan nominal diskon (Rp)' }}
                            </div>
                            @error('amount')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="max_usage" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Maksimal Penggunaan</label>
                        <input type="number" class="form-control rounded-0 bg-subtle border-0 p-3 @error('max_usage') is-invalid @enderror" id="max_usage" name="max_usage" value="{{ old('max_usage', $discount->max_usage) }}" min="1" placeholder="Tidak Terbatas">
                        <div class="form-text small text-muted fst-italic mt-1" style="font-size: 0.75rem;">Kosongkan jika tidak ada batasan jumlah pemakaian.</div>
                        @error('max_usage')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-12 col-md-6">
                            <label for="start_date" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Mulai</label>
                            <input type="date" class="form-control rounded-0 bg-subtle border-0 p-3 @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', optional($discount->start_date)->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="end_date" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Berakhir</label>
                            <input type="date" class="form-control rounded-0 bg-subtle border-0 p-3 @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', optional($discount->end_date)->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-5 p-3 border bg-subtle d-flex align-items-center" style="border-color: #eee !important;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input rounded-0 border-black" id="is_active" name="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label text-uppercase fw-bold ms-2" for="is_active" style="font-size: 0.75rem; letter-spacing: 0.05em; padding-top: 2px;">Aktifkan Diskon Ini</label>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 pt-2">
                        <button type="submit" class="btn btn-primary-custom rounded-0 px-4 py-3 text-uppercase fw-bold w-100 w-md-auto" style="font-size: 0.8rem; letter-spacing: 0.1em;">Simpan Perubahan</button>
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-custom rounded-0 px-4 py-3 text-uppercase fw-bold w-100 w-md-auto text-center" style="font-size: 0.8rem; letter-spacing: 0.1em;">Batal</a>
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