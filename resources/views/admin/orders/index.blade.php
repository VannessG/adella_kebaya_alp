@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')

<div class="container pb-4">
    <div class="row align-items-end">
        <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
            <h1 class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">
                Daftar Penjualan
                @if(session('selected_branch'))
                    <span class="display-5 fw-normal text-uppercase text-black mb-2 font-serif h3">{{ session('selected_branch')->name }}</span>
                @endif
            </h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 0.1em;">Kelola transaksi penjualan</p>
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
        
        <div class="order-list-header d-none d-lg-flex">
            <div class="col-id">ID Pesanan</div>
            <div class="col-customer">Pelanggan</div>
            <div class="col-date">Tanggal</div>
            <div class="col-total">Total</div>
            <div class="col-status">Status</div>
            <div class="col-actions text-end">Aksi</div>
        </div>

        <div class="order-list-body">
            @forelse($orders as $order)
                <div class="order-list-item">
                    <div class="col-id">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">ID Pesanan</span>
                        #{{ $order->order_number }}
                    </div>

                    <div class="col-customer">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Pelanggan</span>
                        <div class="text-black text-uppercase">{{ $order->customer_name }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $order->customer_phone }}</div>
                    </div>

                    <div class="col-date">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Tanggal</span>
                        <span class="text-uppercase small">{{ $order->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="col-total">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Total</span>
                        <span class="fw-bold text-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="col-status">
                        <span class="d-lg-none text-muted small text-uppercase d-block mb-1">Status</span>
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" 
                                    class="form-select form-select-sm rounded-0 text-uppercase fw-bold small clean-dropdown bg-light border-0" 
                                    style="font-size: 0.7rem; padding: 0.4rem 2rem 0.4rem 0.7rem; cursor: pointer;"
                                    onchange="this.form.submit()">
                                @foreach($statusOptions as $key => $label)
                                    <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-actions">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-detail">Detail</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-cart-x fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted text-uppercase small" style="letter-spacing: 0.1em;">Belum ada pesanan masuk.</h6>
                </div>
            @endforelse
        </div>
    </div>
    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection