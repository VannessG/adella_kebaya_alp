@extends('layouts.app')

@section('title', 'Pilih Butik')

@section('content')
{{-- WRAPPER KHUSUS: Putih Bersih dengan Grid Abu-abu Sangat Halus --}}
<div class="d-flex align-items-center justify-content-center" 
     style="min-height: 80vh; background-color: #FFFFFF; background-image: radial-gradient(#E5E5E5 1px, transparent 1px); background-size: 24px 24px;">
    
    <div class="w-100 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                
                {{-- HEADER: Hitam Putih Tegas --}}
                <div class="mb-5 p-5 d-inline-block bg-white border" style="border-color: #E5E5E5;">
                    {{-- Logo Grayscale --}}
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 80px; object-fit: contain; filter: grayscale(100%);" class="mb-4">
                    
                    <h1 class="display-6 fw-normal mb-2 font-serif text-uppercase text-black" style="letter-spacing: 4px; font-size: 1.5rem;">Pilih Lokasi</h1>
                    <p class="mb-0 text-uppercase fw-bold" style="letter-spacing: 2px; font-size: 0.7rem; color: #999;">Adella Kebaya Boutique</p>
                </div>

                {{-- GRID CABANG --}}
                <div class="row g-4 justify-content-center">
                    @foreach($branches as $branch)
                    <div class="col-md-6">
                        {{-- CARD: Putih dengan Hover Monokrom --}}
                        <div class="card h-100 text-center p-5 cursor-pointer border hover-mono bg-white" 
                             onclick="selectBranch({{ $branch->id }})"
                             style="cursor: pointer; transition: all 0.4s ease; border-color: #E5E5E5;">
                            
                            <div class="card-body d-flex flex-column align-items-center p-0">
                                {{-- Icon Box: Abu-abu Muda --}}
                                <div class="mb-4 d-flex align-items-center justify-content-center icon-box" 
                                     style="width: 70px; height: 70px; background-color: #F4F4F4; transition: 0.3s; border: 1px solid #E5E5E5;">
                                    <i class="bi bi-shop display-6 text-black"></i>
                                </div>

                                {{-- Kota --}}
                                <h3 class="card-title font-serif fw-bold mb-3 text-uppercase text-black" style="letter-spacing: 2px;">{{ $branch->city }}</h3>
                                
                                {{-- Alamat --}}
                                <p class="mb-3 small" style="min-height: 40px; font-weight: 400; color: #666;">
                                    {{ $branch->address }}
                                </p>

                                {{-- Telepon --}}
                                <p class="mb-4 small fw-bold" style="letter-spacing: 1px; color: #333;">
                                    {{ $branch->phone }}
                                </p>

                                {{-- Tombol: Outline Hitam --}}
                                <div class="mt-auto w-100">
                                    <button class="btn w-100 btn-select-branch" 
                                            style="border: 1px solid #000; color: #000; background: transparent; padding: 14px; font-size: 0.7rem;">
                                        MASUK BUTIK
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-5 small">
                    <p style="letter-spacing: 2px; font-size: 0.7rem; color: #999; text-transform: uppercase;">&copy; {{ date('Y') }} Adella Kebaya</p>
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

{{-- Style Khusus Halaman Ini --}}
<style>
    /* Efek Hover Monokrom Tegas */
    .hover-mono:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
        /* Border berubah jadi Hitam Pekat saat hover */
        border-color: #000000 !important;
    }

    /* Ubah warna tombol jadi hitam solid saat card di-hover */
    .hover-mono:hover .btn-select-branch {
        background-color: #000000 !important;
        color: #ffffff !important;
    }

    /* Ubah background icon jadi Putih saat hover dengan border hitam */
    .hover-mono:hover .icon-box {
        background-color: #ffffff !important;
        border-color: #000000 !important;
    }
</style>
@endsection

@section('scripts')
<script>
    function selectBranch(branchId) {
        document.getElementById('selectedBranch').value = branchId;
        document.getElementById('branchForm').submit();
    }
</script>
@endsection