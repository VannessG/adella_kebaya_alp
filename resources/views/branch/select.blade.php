@extends('layouts.app')

@section('title', 'Pilih Butik')

@section('content')

<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: var(--bg-surface); background-image: radial-gradient(var(--border-color) 1px, transparent 1px); background-size: 24px 24px;">
    <div class="w-100 py-3">
        <div class="row justify-content-center m-0">
            <div class="col-lg-8 text-center">
                <div class="mb-5 p-5 d-inline-block bg-white border" style="border-color: var(--border-color);">
                    <img src="{{ asset('images/logo.png') }}" alt="Adella Kebaya" style="height: 80px; object-fit: contain; filter: grayscale(100%);" class="mb-4">
                    <h1 class="display-6 fw-normal mb-2 font-serif text-uppercase text-black" style="letter-spacing: 0.2em; font-size: 1.5rem;">Pilih Lokasi</h1>
                    <p class="mb-0 text-uppercase fw-bold text-muted" style="letter-spacing: 0.2em; font-size: 0.7rem;">Adella Kebaya Boutique</p>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach($branches as $branch)
                    <div class="col-md-6">
                        <div class="card h-100 text-center p-5 cursor-pointer border rounded-0 hover-mono bg-white" onclick="selectBranch({{ $branch->id }})" style="cursor: pointer; transition: all 0.4s ease; border-color: var(--border-color);">
                            <div class="card-body d-flex flex-column align-items-center p-0">
                                <div class="mb-4 d-flex align-items-center justify-content-center icon-box rounded-0" style="width: 70px; height: 70px; background-color: var(--bg-subtle); transition: 0.3s; border: 1px solid var(--border-color);">
                                    <i class="bi bi-shop display-6 text-black"></i>
                                </div>

                                <h3 class="card-title font-serif fw-normal mb-3 text-uppercase text-black" style="letter-spacing: 0.15em;">{{ $branch->city }}</h3>
                                <p class="mb-3 small text-muted" style="min-height: 40px; font-weight: 300;">{{ $branch->address }}</p>
                                <p class="mb-5 small fw-bold text-black" style="letter-spacing: 0.1em;">{{ $branch->phone }}</p>

                                <div class="mt-auto w-100">
                                    <button class="btn btn-outline-custom w-100 btn-select-branch rounded-0" style="padding: 14px; font-size: 0.75rem;">MASUK BUTIK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-5 small">
                    <p class="text-muted text-uppercase" style="letter-spacing: 0.2em; font-size: 0.65rem;">&copy; {{ date('Y') }} Adella Kebaya</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Form --}}
<form id="branchForm" action="{{ route('branch.select') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="branch_id" id="selectedBranch">
</form>
@endsection

@section('scripts')
<script>
    function selectBranch(branchId) {
        document.getElementById('selectedBranch').value = branchId;
        document.getElementById('branchForm').submit();
    }
</script>
@endsection