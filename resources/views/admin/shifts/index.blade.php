@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">
                Rekap Absensi
                @if(session('selected_branch'))
                    <span class="text-muted fs-4">| {{ session('selected_branch')->name }}</span>
                @endif
            </h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Laporan kehadiran staf harian</p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary-custom rounded-0 w-100 w-md-auto py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Jadwal Baru
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    @if(session('success'))
        <div class="alert rounded-0 border-black bg-white text-black d-flex align-items-center mb-4 p-3 mx-3 mx-md-0" role="alert">
            <i class="bi bi-check-circle me-3 fs-5"></i>
            <div class="small text-uppercase" style="letter-spacing: 0.05em;">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border rounded-0 bg-white mb-5 mx-0 mx-md-0 shadow-sm" style="border-color: #E0E0E0;">
        <div class="card-body p-4">
            <form action="{{ route('admin.shifts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ request('start_date') }}">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ request('end_date') }}">
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button type="submit" class="btn btn-primary-custom w-100 rounded-0 py-3 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                            <i class="bi bi-filter me-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.shifts.index') }}" class="btn btn-outline-custom w-100 rounded-0 py-3 text-uppercase fw-bold small text-center" style="letter-spacing: 0.1em;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border rounded-0 shadow-sm bg-white mx-0 mx-md-0">
        <div class="shift-list-header d-none d-lg-flex">
            <div class="col-date">Tanggal</div>
            <div class="col-branch">Cabang</div>
            <div class="col-time">Jam Kerja</div>
            <div class="col-total">Total Staff</div>
            <div class="col-present">Hadir</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="shift-list-body">
            @forelse($shifts as $shift)
                <div class="shift-list-item">
                    <div class="col-date">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Tanggal</span>
                        {{ $shift->view_date }}
                    </div>

                    <div class="col-branch">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Cabang</span>
                        <span class="badge rounded-0 bg-white text-black border fw-normal text-uppercase" style="font-size: 0.65rem;">
                            {{ $shift->branch->name }}
                        </span>
                    </div>

                    <div class="col-time">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Jam Kerja</span>
                        {{ $shift->view_time }}
                    </div>

                    <div class="col-total">
                        <span class="d-lg-none text-muted small text-uppercase d-inline mr-2">Total: </span>
                        <span class="fw-bold">{{ $shift->view_total_staff }}</span>
                    </div>

                    <div class="col-present">
                        <span class="d-lg-none text-muted small text-uppercase d-inline mr-2">Hadir: </span>
                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border bg-black text-white border-black" style="font-size: 0.65rem;">
                            {{ $shift->view_present_count }}
                        </span>
                    </div>

                    <div class="col-actions">
                        <a href="{{ route('admin.shifts.show', $shift->id) }}" class="btn btn-detail">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada rekapan shift dalam rentang waktu ini.</h6>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection