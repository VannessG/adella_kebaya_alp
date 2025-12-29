@extends('layouts.app')

@section('title', 'Manajemen Diskon')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-8 text-center text-md-start">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Manajemen Diskon</h1>
            <p class="text-muted text-uppercase mb-0" style="letter-spacing: 0.1em; font-size: 0.9rem;">Kelola kode promo dan potongan harga</p>
        </div>
        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
            {{-- TOMBOL CREATE --}}
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary-custom rounded-0 py-3 px-4 text-uppercase fw-bold" style="letter-spacing: 0.1em; font-size: 0.85rem;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Diskon
            </a>
        </div>
    </div>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    
    {{-- TABEL DATA --}}
    <div class="card border rounded-0 shadow-none bg-white" style="border-color: #E0E0E0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                    <thead class="bg-subtle">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted font-weight-bold" style="letter-spacing: 0.1em; font-size: 0.8rem;">Nama Promo</th>
                            <th class="py-3 text-uppercase text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.8rem;">Kode</th>
                            <th class="py-3 text-uppercase text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.8rem;">Tipe & Nilai</th>
                            <th class="py-3 text-uppercase text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.8rem;">Periode</th>
                            <th class="py-3 text-uppercase text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.8rem;">Status</th>
                            <th class="py-3 text-uppercase text-muted font-weight-bold text-center" style="letter-spacing: 0.1em; font-size: 0.8rem;">Digunakan</th>
                            <th class="pe-4 py-3 text-uppercase text-muted font-weight-bold text-end" style="letter-spacing: 0.1em; font-size: 0.8rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discounts as $discount)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            {{-- NAMA --}}
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-black text-uppercase" style="letter-spacing: 0.05em; font-size: 0.9rem;">{{ $discount->name }}</span>
                            </td>

                            {{-- KODE --}}
                            <td class="py-3 text-center">
                                @if($discount->code)
                                    <span class="badge rounded-0 bg-white border border-black text-black font-monospace px-2 py-1" style="font-size: 0.8rem;">{{ $discount->code }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            {{-- TIPE & NILAI --}}
                            <td class="py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    {{-- Menggunakan data olahan Controller --}}
                                    <span class="badge rounded-0 border me-2 text-uppercase" 
                                          style="font-size: 0.75rem; letter-spacing: 0.05em; 
                                          @if($discount->type === 'percentage') background-color: #000; color: #fff; border-color: #000;
                                          @else background-color: #fff; color: #000; border-color: #000; @endif">
                                        {{ $discount->view_type_label }}
                                    </span>
                                    
                                    {{-- Menggunakan data olahan Controller --}}
                                    <span class="fw-bold text-black" style="font-size: 0.9rem;">
                                        {{ $discount->view_amount_formatted }}
                                    </span>
                                </div>
                            </td>

                            {{-- PERIODE --}}
                            <td class="py-3">
                                <div class="text-uppercase text-center" style="font-size: 0.9rem; color: #000000;">
                                    {{-- Menggunakan data olahan Controller --}}
                                    {{ $discount->view_period }}
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td class="py-3 text-center">
                                {{-- Menggunakan data olahan Controller --}}
                                <span class="badge rounded-0 px-2 py-1 text-uppercase fw-normal border" 
                                      style="font-size: 0.8rem; letter-spacing: 0.05em;
                                      @if($discount->view_status_active) background-color: #000; color: #fff; border-color: #000;
                                      @else background-color: #fff; color: #dc3545; border-color: #dc3545; @endif">
                                    {{ $discount->view_status_label }}
                                </span>
                            </td>

                            {{-- DIGUNAKAN --}}
                            <td class="py-3 text-center" style="font-size: 0.9rem;">
                                {{ $discount->used_count }} / {{ $discount->max_usage ?? '∞' }}
                            </td>

                            {{-- AKSI --}}
                            <td class="pe-4 py-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" 
                                       class="btn btn-outline-dark rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                       style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-0 px-3 py-1 text-uppercase fw-bold" 
                                                style="font-size: 0.75rem; letter-spacing: 0.05em;"
                                                onclick="return confirm('Yakin ingin menghapus diskon ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-5">
        {{ $discounts->links() }}
    </div>
</div>
@endsection