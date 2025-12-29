@extends('layouts.app')

@section('title', 'Manajemen Penyewaan')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Daftar Penyewaan</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola transaksi sewa kebaya</p>
        </div>
    </div>
    <div class="d-md-none" style="width: 60px; height: 1px; background-color: #000; margin: 15px auto;"></div>
</div>

<div class="container pb-5">
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. Sewa</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pelanggan</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Periode</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Total</th>
                            <th class="py-3 text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Status</th>
                            <th class="pe-4 py-3 text-end text-uppercase small text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rents as $rent)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            {{-- NO. SEWA --}}
                            <td class="ps-4 py-3 fw-bold text-black" style="font-family: 'Jost', sans-serif;">
                                {{ $rent->rent_number }}
                            </td>

                            {{-- PELANGGAN --}}
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-black text-uppercase small" style="letter-spacing: 0.05em;">{{ $rent->customer_name }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $rent->user->email ?? 'Guest' }}</small>
                                </div>
                            </td>

                            {{-- PERIODE --}}
                            <td class="py-3">
                                <div class="text-muted small text-uppercase" style="line-height: 1.4;">
                                    {{ $rent->start_date->format('d M Y') }} <br>
                                    <span class="opacity-50">s/d</span> {{ $rent->end_date->format('d M Y') }}
                                </div>
                            </td>

                            {{-- TOTAL --}}
                            <td class="py-3 fw-bold text-black small">Rp {{ number_format($rent->total_amount, 0, ',', '.') }}</td>

                            {{-- STATUS DROPDOWN --}}
                            <td class="py-3">
                                <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    {{-- Dropdown seragam & rapi --}}
                                    <select name="status" 
                                            class="form-select form-select-sm rounded-0 text-uppercase fw-bold small clean-dropdown" 
                                            onchange="this.form.submit()">
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $rent->status == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>

                            {{-- AKSI --}}
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('admin.rents.show', $rent) }}" class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($rents->hasPages())
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
            {{ $rents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection