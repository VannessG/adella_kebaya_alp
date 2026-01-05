@extends('layouts.app')

@section('title', 'Manajemen Diskon')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">Manajemen Diskon</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola kode promo dan potongan harga</p>
        </div>
        <div class="col-12 col-md-4 text-center text-md-end">
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary-custom rounded-0 w-100 w-md-auto py-3 px-4 text-uppercase fw-bold small" style="letter-spacing: 0.1em;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Diskon
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

    <div class="card border rounded-0 shadow-sm bg-white mx-0 mx-md-0">
        <div class="discount-list-header d-none d-lg-flex">
            <div class="col-name">Nama Promo</div>
            <div class="col-code">Kode</div>
            <div class="col-value">Nilai</div>
            <div class="col-period">Periode</div>
            <div class="col-status">Status</div>
            <div class="col-usage">Used</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="discount-list-body">
            @forelse($discounts as $discount)
                <div class="discount-list-item">
                    <div class="col-name">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Nama Promo</span>
                        <h6 class="fw-bold text-black text-uppercase mb-0">{{ $discount->name }}</h6>
                    </div>

                    <div class="col-code">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Kode</span>
                        @if($discount->code)
                            <span class="badge rounded-0 bg-white border border-black text-black font-monospace px-2 py-1">{{ $discount->code }}</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </div>
                    
                    <div class="col-value">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Nilai</span>
                        <div class="d-flex align-items-center">
                            <span class="badge rounded-0 border me-2 text-uppercase text-black" style="font-size: 0.65rem;">{{ $discount->view_type_label }}</span>
                            <span class="fw-bold text-black">{{ $discount->view_amount_formatted }}</span>
                        </div>
                    </div>

                    <div class="col-period">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Periode</span>
                        <span class="small text-uppercase">{{ $discount->view_period }}</span>
                    </div>

                    <div class="col-status">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Status</span>
                        <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border" style="font-size: 0.7rem; letter-spacing: 0.05em;
                                @if($discount->view_status_active) background-color: #000; color: #fff; border-color: #000;
                                @else background-color: #fff; color: #dc3545; border-color: #dc3545; @endif">
                            {{ $discount->view_status_label }}
                        </span>
                    </div>

                    <div class="col-usage">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Terpakai</span>
                        <span class="small">{{ $discount->used_count }} / {{ $discount->max_usage ?? '∞' }}</span>
                    </div>

                    <div class="col-actions">
                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-action btn-edit"><i class="bi bi-pencil me-1"></i> <span class="d-lg-none ms-2">Edit</span></a>

                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus diskon ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-delete"><i class="bi bi-trash me-1"></i> <span class="d-lg-none ms-2">Hapus</span></button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-tag fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada diskon aktif.</h6>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-5">
        {{ $discounts->links() }}
    </div>
</div>
@endsection