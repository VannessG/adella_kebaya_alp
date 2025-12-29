@extends('layouts.app')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Edit Presensi</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">
                <span class="fw-bold text-black">{{ $shift->branch->name }}</span> | 
                {{ date('d F Y', strtotime($shift->shift_day)) }}
            </p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-left: auto;"></div>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border rounded-0 bg-white p-4" style="border-color: var(--border-color);">
                <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4 border-bottom pb-4" style="border-color: #f0f0f0 !important;">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label small text-uppercase fw-bold text-muted">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control rounded-0 bg-subtle border-0" value="{{ $shift->start_time }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-uppercase fw-bold text-muted">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control rounded-0 bg-subtle border-0" value="{{ $shift->end_time }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-subtle">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pegawai</th>
                                    <th class="py-3 text-uppercase small text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.7rem;">Ubah Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $emp)
                                <tr style="border-bottom: 1px solid #f0f0f0;">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-black small text-uppercase" style="letter-spacing: 0.05em;">{{ $emp->name }}</div>
                                        <small class="text-muted font-monospace" style="font-size: 0.65rem;">{{ $emp->nik }}</small>
                                    </td>
                                    <td class="text-center py-3">
                                        <input type="hidden" name="attendance_data[{{ $emp->id }}]" id="input-{{ $emp->id }}" value="{{ $emp->edit_status_value }}">
                                        <button type="button" class="btn rounded-0 small fw-bold text-uppercase toggle-btn {{ $emp->edit_is_present ? 'btn-black-active' : 'btn-outline-custom' }}" 
                                                id="btn-{{ $emp->id }}" onclick="toggleAttendance({{ $emp->id }})"style="font-size: 0.65rem; letter-spacing: 0.05em; padding: 0.5rem 1.5rem;">
                                            @if($emp->edit_is_present)
                                                <i class="bi bi-check me-1"></i> Hadir
                                            @else
                                                <i class="bi bi-x me-1"></i> Tidak Hadir
                                            @endif
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top" style="border-color: #f0f0f0 !important;">
                        <a href="{{ route('admin.shifts.show', $shift->id) }}" class="btn btn-outline-custom rounded-0 py-2 px-4 text-uppercase fw-bold small" style="font-size: 0.7rem; letter-spacing: 0.1em;">
                            <i class="bi bi-x me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary-custom rounded-0 py-3 px-5 text-uppercase fw-bold small" style="font-size: 0.8rem; letter-spacing: 0.1em;">Simpan Perubahan</button>
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
        btn.innerHTML = '<i class="bi bi-check me-1"></i> Hadir';
    } else {
        input.value = 'tidak_hadir';
        btn.classList.remove('btn-black-active');
        btn.classList.add('btn-outline-custom');
        btn.innerHTML = '<i class="bi bi-x me-1"></i> Tidak Hadir';
    }
}
</script>
@endsection