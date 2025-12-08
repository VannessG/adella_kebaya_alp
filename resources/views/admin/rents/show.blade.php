@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rent->rent_number)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text mb-1">Detail Sewa</h1>
                    <p class="text-muted mb-0">No. Sewa: {{ $rent->rent_number }}</p>
                </div>
                <span class="badge fs-6 
                    @if($rent->status == 'completed') bg-success
                    @elseif($rent->status == 'active') bg-info
                    @elseif($rent->status == 'confirmed') bg-warning
                    @elseif($rent->status == 'pending') bg-secondary
                    @elseif($rent->status == 'cancelled') bg-danger
                    @elseif($rent->status == 'overdue') bg-dark
                    @endif">
                    {{ $statusOptions[$rent->status] }}
                </span>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Nama:</strong> {{ $rent->customer_name }}</p>
                            <p class="mb-2"><strong>Telepon:</strong> {{ $rent->customer_phone }}</p>
                            <p class="mb-0"><strong>Alamat:</strong> {{ $rent->customer_address }}</p>
                            @if($rent->user)
                                <p class="mb-0 mt-2"><strong>Email:</strong> {{ $rent->user->email }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Sewa</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Tanggal Mulai:</strong> {{ $rent->start_date->format('d M Y') }}</p>
                            <p class="mb-2"><strong>Tanggal Selesai:</strong> {{ $rent->end_date->format('d M Y') }}</p>
                            <p class="mb-2"><strong>Durasi:</strong> {{ $rent->total_days }} hari</p>
                            <p class="mb-2"><strong>No. Sewa:</strong> {{ $rent->rent_number }}</p>
                            <p class="mb-2"><strong>Tipe Pengiriman:</strong> 
                                {{ $rent->delivery_type === 'delivery' ? 'Dikirim' : 'Ambil di Tempat' }}
                            </p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge 
                                    @if($rent->status == 'completed') bg-success
                                    @elseif($rent->status == 'active') bg-info
                                    @elseif($rent->status == 'confirmed') bg-warning
                                    @elseif($rent->status == 'pending') bg-secondary
                                    @elseif($rent->status == 'cancelled') bg-danger
                                    @elseif($rent->status == 'overdue') bg-dark
                                    @endif">
                                    {{ $statusOptions[$rent->status] }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold">Detail Produk</h5>
                </div>
                <div class="card-body">
                    @if($rent->products->isEmpty())
                        <p class="text-muted">Tidak ada produk dalam sewa ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga/hari</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rent->products as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                                         class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <b>{{ $product->name }}</b>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $product->pivot->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($product->pivot->price_per_day, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Biaya Sewa:</td>
                                        <td class="text-end">Rp {{ number_format($rent->total_amount - $rent->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($rent->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Biaya Pengiriman:</td>
                                        <td class="text-end">Rp {{ number_format($rent->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text fs-5">
                                            Rp {{ number_format($rent->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.rents.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sewa
                </a>
                
                <div class="d-flex gap-2">
                    @if($rent->status == 'active' && now()->greaterThanOrEqualTo($rent->end_date))
                        <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Tandai sewa sebagai selesai?')">
                                <i class="bi bi-check-circle"></i> Tandai Selesai
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.rents.update-status', $rent) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Ubah Status</option>
                            @foreach($statusOptions as $value => $label)
                                @if($value != $rent->status)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection