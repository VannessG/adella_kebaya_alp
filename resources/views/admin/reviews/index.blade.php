@extends('layouts.app')

@section('title', 'Manajemen Review')

@section('content')
<div class="container">
    <h1 class="fw-bold text mb-4">Manajemen Review</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $review->product->image_url }}" alt="{{ $review->product->name }}" 
                                             class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <small class="fw-semibold">{{ $review->product->name }}</small>
                                            <br>
                                            <small class="text-muted">
                                                @if($review->order_id)
                                                    Beli: {{ $review->order->order_number }}
                                                @else
                                                    Sewa: {{ $review->rent->rent_number }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $review->user->name }}</td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                </td>
                                <td>
                                    <small>{{ Str::limit($review->comment, 50) }}</small>
                                    @if($review->image)
                                        <br>
                                        <a href="{{ $review->image_url }}" target="_blank" class="small">
                                            <i class="bi bi-image"></i> Foto
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $review->is_approved ? 'bg-success' : 'bg-warning' }}">
                                        {{ $review->is_approved ? 'Disetujui' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $review->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-success" title="Setujui">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus review ini?')">
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
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
@endsection