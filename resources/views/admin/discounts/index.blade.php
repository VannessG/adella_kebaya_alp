@extends('layouts.app')

@section('title', 'Manajemen Diskon')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text">Manajemen Diskon</h1>
        <a href="{{ route('admin.discounts.create') }}" class="btn">
            <i class="bi bi-plus-circle"></i> Tambah Diskon
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Digunakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discounts as $discount)
                            <tr>
                                <td>{{ $discount->name }}</td>
                                <td>{{ $discount->code ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $discount->type === 'percentage' ? 'bg-info' : 'bg-primary' }}">
                                        {{ $discount->type === 'percentage' ? 'Persentase' : 'Nominal' }}
                                    </span>
                                </td>
                                <td>
                                    @if($discount->type === 'percentage')
                                        {{ $discount->amount }}%
                                    @else
                                        Rp {{ number_format($discount->amount, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        {{ $discount->start_date->format('d M Y') }}<br>
                                        s/d {{ $discount->end_date->format('d M Y') }}
                                    </small>
                                </td>
                                <td>
                                    @php
                                        $isActive = $discount->is_active && 
                                                   now()->between($discount->start_date, $discount->end_date);
                                    @endphp
                                    <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                                        {{ $isActive ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>{{ $discount->used_count }} / {{ $discount->max_usage ?? '∞' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
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
            
            <div class="d-flex justify-content-center">
                {{ $discounts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection