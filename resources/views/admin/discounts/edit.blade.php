@extends('layouts.app')

@section('title', 'Edit Diskon')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold text mb-4">Edit Diskon</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Diskon</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $discount->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Diskon (Opsional)</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $discount->code) }}">
                            <div class="form-text">Kosongkan untuk diskon otomatis berlaku</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Diskon</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>
                                            Persentase (%)
                                        </option>
                                        <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>
                                            Nominal Tetap
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Nilai Diskon</label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount', $discount->amount) }}" 
                                           min="0" step="0.01" required>
                                    <div class="form-text" id="amountHelp">
                                        {{ $discount->type === 'percentage' ? 'Masukkan persentase diskon (0-100%)' : 'Masukkan nominal diskon (Rp)' }}
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="max_usage" class="form-label">Maksimal Penggunaan (Opsional)</label>
                            <input type="number" class="form-control @error('max_usage') is-invalid @enderror" 
                                   id="max_usage" name="max_usage" value="{{ old('max_usage', $discount->max_usage) }}" min="1">
                            <div class="form-text">Kosongkan untuk penggunaan tidak terbatas</div>
                            @error('max_usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date', $discount->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Berakhir</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" 
                                           value="{{ old('end_date', $discount->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan Diskon</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn">Update Diskon</button>
                            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
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