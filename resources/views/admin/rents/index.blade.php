@extends('layouts.app')

@section('title', 'Manajemen Penyewaan')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">
                Daftar Penyewaan
                @if(session('selected_branch'))
                    <span class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">{{ session('selected_branch')->name }}</span>
                @endif
            </h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola transaksi penyewaan</p>
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

    <div class="card border rounded-0 shadow-sm bg-white mx-0 mx-md-0">
        <div class="rent-list-header d-none d-lg-flex">
            <div class="col-rent-no">No. Sewa</div>
            <div class="col-customer">Pelanggan</div>
            <div class="col-period">Periode</div>
            <div class="col-total">Total</div>
            <div class="col-status">Status</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="rent-list-body">
            @forelse($rents as $rent)
                <div class="rent-list-item">
                    
                    <div class="col-rent-no">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">No. Sewa</span>
                        {{ $rent->rent_number }}
                    </div>

                    <div class="col-customer">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Pelanggan</span>
                        <div class="fw-bold text-black small text-uppercase">{{ $rent->customer_name }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $rent->user->email ?? 'Guest' }}</div>
                    </div>

                    <div class="col-period">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Periode</span>
                        <div class="text-uppercase small" style="line-height: 1.4;">
                            {{ $rent->start_date->format('d M Y') }} <br class="d-none d-lg-block"> 
                            <span class="text-muted d-lg-none mx-1">-</span> 
                            <span class="text-muted d-none d-lg-inline">s/d</span> 
                            {{ $rent->end_date->format('d M Y') }}
                        </div>
                    </div>

                    <div class="col-total">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Total</span>
                        Rp {{ number_format($rent->total_amount, 0, ',', '.') }}
                    </div>

                    <div class="col-status">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Status</span>
                        <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" 
                                    class="form-select form-select-sm rounded-0 text-uppercase fw-bold small clean-dropdown bg-light border-0" 
                                    style="font-size: 0.7rem; padding: 0.4rem 2rem 0.4rem 0.7rem; cursor: pointer;"
                                    onchange="this.form.submit()">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $rent->status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="col-actions">
                        <a href="{{ route('admin.rents.show', $rent) }}" class="btn btn-detail">Detail</a>
                    </div>

                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada penyewaan.</h6>
                </div>
            @endforelse
        </div>
    </div>
    @if($rents->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $rents->links() }}
        </div>
    @endif
</div>
@endsection