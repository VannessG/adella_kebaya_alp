@extends('layouts.branch-selector')

@section('title', 'Pilih Cabang')

@section('content')
<div class="container py-5 min-vh-100 d-flex align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-lg-8 text-center">
            <div class="mb-5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 120px;" class="mb-4">
                <h1 class="display-4 fw-bold text-primary-custom mb-3">Pilih Cabang</h1>
                <p class="lead text-muted">Silakan pilih cabang terdekat untuk melanjutkan</p>
            </div>

            <div class="row g-4">
                @foreach($branches as $branch)
                <div class="col-md-6">
                    <div class="card branch-card border-0 shadow-lg bg-white" 
                         onclick="selectBranch({{ $branch->id }})">
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <i class="bi bi-shop display-4 text-primary-custom"></i>
                            </div>
                            <h3 class="card-title fw-bold text-primary-custom mb-3">{{ $branch->city }}</h3>
                            <p class="card-text text-muted mb-3">
                                <i class="bi bi-geo-alt me-2"></i> {{ $branch->address }}
                            </p>
                            <p class="card-text mb-4">
                                <i class="bi bi-telephone me-2"></i> {{ $branch->phone }}
                            </p>
                            <button class="btn btn-branch btn-lg px-5 py-3" 
                                    onclick="event.stopPropagation(); selectBranch({{ $branch->id }})">
                                <i class="bi bi-check-circle me-2"></i> Pilih Cabang Ini
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<form id="branchForm" action="{{ route('branch.select') }}" method="POST">
    @csrf
    <input type="hidden" name="branch_id" id="selectedBranch">
</form>

@push('scripts')
<script>
    function selectBranch(branchId) {
        document.getElementById('selectedBranch').value = branchId;
        document.getElementById('branchForm').submit();
    }
</script>
@endpush
@endsection