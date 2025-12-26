@extends('layouts.app')

@section('title', 'Tambah Diskon')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold text mb-4">Tambah Diskon Baru</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.discounts.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Diskon</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
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
                                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>
                                            Persentase (%)
                                        </option>
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
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
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           min="0" step="0.01" required>
                                    <div class="form-text" id="amountHelp">
                                        Masukkan nilai diskon
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
                                   id="max_usage" name="max_usage" value="{{ old('max_usage') }}" min="1">
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
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Tanggal Berakhir</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Aktifkan Diskon</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn">Simpan Diskon</button>
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