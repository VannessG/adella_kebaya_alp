@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Rekap Shift</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Laporan kehadiran staf harian</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            {{-- TOMBOL CREATE --}}
            <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary-custom rounded-0 py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Shift Baru
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4 p-3" role="alert">
            <i class="bi bi-check-circle me-3 fs-5"></i>
            <div class="small text-uppercase" style="letter-spacing: 0.05em;">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTER FORM --}}
    <div class="card border rounded-0 bg-white mb-5" style="border-color: #E0E0E0;">
        <div class="card-body p-4">
            <form action="{{ route('admin.shifts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                            <i class="bi bi-filter me-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-custom w-100 rounded-0 py-3 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Cabang</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Jam Kerja</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Total Staff</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Hadir</th>
                            <th class="pe-4 py-3 text-uppercase small text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="ps-4 py-3">
                                    {{-- Menggunakan properti dari Controller --}}
                                    <span class="text-black fw-bold small text-uppercase" style="letter-spacing: 0.05em;">{{ $shift->view_date }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-0 border text-black bg-white px-2 py-1 text-uppercase" 
                                          style="font-size: 0.6rem; letter-spacing: 0.05em; border-color: #ddd;">
                                        {{ $shift->branch->name }}
                                    </span>
                                </td>
                                <td class="py-3 text-muted small text-uppercase">
                                    {{-- Menggunakan properti dari Controller --}}
                                    {{ $shift->view_time }}
                                </td>
                                <td class="py-3 text-center text-black small">
                                    {{-- Menggunakan properti dari Controller --}}
                                    {{ $shift->view_total_staff }}
                                </td>
                                <td class="py-3 text-center">
                                    {{-- Menggunakan properti dari Controller --}}
                                    <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-black text-white border-black" 
                                          style="font-size: 0.6rem; letter-spacing: 0.05em;">
                                        {{ $shift->view_present_count }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('admin.shifts.show', $shift->id) }}" 
                                       class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                       style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted small text-uppercase" style="letter-spacing: 0.1em;">Belum ada rekapan shift dalam rentang waktu ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection