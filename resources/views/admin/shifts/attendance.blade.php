@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Presensi Harian</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                <span class="fw-bold text-black">{{ $branch->name }}</span> | 
                {{ date('d F Y', strtotime($shift_day)) }} | 
                <span class="font-monospace small">{{ $start_time }} - {{ $end_time }}</span>
            </p>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5 px-0 px-md-3">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card border rounded-0 bg-white shadow-sm" style="border-color: var(--border-color);">
                <form action="{{ route('admin.shifts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <input type="hidden" name="shift_day" value="{{ $shift_day }}">
                    <input type="hidden" name="start_time" value="{{ $start_time }}">
                    <input type="hidden" name="end_time" value="{{ $end_time }}">

                    <div class="attendance-list-header d-none d-md-flex">
                        <div class="col-name">Pegawai</div>
                        <div class="col-status text-center w-100">Status Kehadiran</div>
                    </div>

                    <div class="attendance-list-body">
                        @foreach($employees as $emp)
                        <div class="attendance-list-item">
                            <div class="col-name">
                                <div class="fw-bold text-black text-uppercase mb-1" style="letter-spacing: 0.05em;">{{ $emp->name }}</div>
                                <small class="font-monospace text-muted" style="font-size: 0.75rem;">{{ $emp->nik }}</small>
                            </div>

                            <div class="col-status d-flex justify-content-md-center justify-content-start w-100">
                                <input type="hidden" name="attendance_data[{{ $emp->id }}]" id="input-{{ $emp->id }}" value="tidak_hadir">
                                <button type="button" 
                                        class="btn btn-outline-custom rounded-0 fw-bold text-uppercase btn-toggle-attendance" 
                                        id="btn-{{ $emp->id }}" 
                                        onclick="toggleAttendance({{ $emp->id }})">
                                    <i class="bi bi-x me-1"></i> Tidak Hadir
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white border-top p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <a href="{{ route('admin.shifts.create') }}" class="btn btn-outline-custom rounded-0 py-2 px-4 text-uppercase fw-bold w-100 w-md-auto text-center" style="font-size: 0.7rem; letter-spacing: 0.1em;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-custom rounded-0 py-3 px-5 text-uppercase fw-bold w-100 w-md-auto" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                Simpan Rekapan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAttendance(id) {
    const btn = document.getElementById('btn-' + id);
    const input = document.getElementById('input-' + id);

    if (input.value === 'tidak_hadir') {
        input.value = 'hadir';
        btn.classList.remove('btn-outline-custom'); 
        btn.classList.add('btn-black-active'); 

        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-dark');
        
        btn.innerHTML = '<i class="bi bi-check me-1"></i> Hadir';
    } else {
        input.value = 'tidak_hadir';
        btn.classList.remove('btn-black-active');
        btn.classList.add('btn-outline-custom');

        btn.classList.remove('btn-dark');
        btn.classList.add('btn-outline-dark');

        btn.innerHTML = '<i class="bi bi-x me-1"></i> Tidak Hadir';
    }
}
</script>
@endsection