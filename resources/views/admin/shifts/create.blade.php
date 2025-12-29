@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Jadwal Harian</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Atur jadwal dan lokasi</p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border rounded-0 bg-white p-4 p-md-5" style="border-color: var(--border-color);">
                
                <form action="{{ route('admin.shifts.attendance') }}" method="GET">
                    
                    {{-- Pilih Cabang --}}
                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pilih Cabang</label>
                        <select name="branch_id" class="form-select rounded-0 bg-subtle border-0 p-3" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Shift --}}
                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Shift</label>
                        <input type="date" name="shift_day" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ date('Y-m-d') }}" required>
                    </div>

                    {{-- Jam Kerja --}}
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control rounded-0 bg-subtle border-0 p-3" value="08:00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control rounded-0 bg-subtle border-0 p-3" value="16:00" required>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-primary-custom rounded-0 py-3 text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Lanjut ke Absensi <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-custom rounded-0 py-3 text-uppercase fw-bold text-center text-decoration-none" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection