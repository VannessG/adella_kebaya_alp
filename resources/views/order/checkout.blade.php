@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Checkout</h2>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Keranjang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengiriman & Pembayaran</li>
            </ol>
        </nav>
    </div>

    <div class="col-lg-8">
        <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
            @csrf
            {{-- Hidden Inputs for Direct Checkout --}}
            @if(isset($isDirectCheckout) && $isDirectCheckout)
                @foreach($cartItems as $item)
                    <input type="hidden" name="direct_products[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                @endforeach
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-vcard me-2 text-primary"></i>Informasi Penerima</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">No. Telepon (WhatsApp)</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ $user->phone }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Alamat Pengiriman</label>
                            <textarea name="customer_address" class="form-control" rows="3" required>{{ $user->address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-truck me-2 text-primary"></i>Metode Pengiriman</h5>
                    
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check shipping-method" name="delivery_type" id="pickup" value="pickup" checked data-cost="0">
                        <label class="btn btn-outline-secondary py-3" for="pickup">
                            <i class="bi bi-shop d-block fs-4 mb-1"></i>
                            Ambil di Toko
                        </label>
                    
                        <input type="radio" class="btn-check shipping-method" name="delivery_type" id="delivery" value="delivery" data-cost="0"> <label class="btn btn-outline-secondary py-3" for="delivery">
                            <i class="bi bi-box-seam d-block fs-4 mb-1"></i>
                            Jasa Ekspedisi (JNE/JNT)
                        </label>
                    </div>

                    <div id="courier-options" class="mt-4" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Pilih Kurir</label>
                                <select class="form-select" id="courier_code" name="courier_code">
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Layanan</label>
                                <select class="form-select" id="courier_service" name="courier_service">
                                    <option value="">Pilih Layanan...</option>
                                    </select>
                            </div>
                            <input type="hidden" name="district_id" id="district_id" value="114"> </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Metode Pembayaran</h5>
                    
                    <div class="d-flex flex-column gap-2">
                        @foreach($paymentMethods as $method)
                        <div class="form-check p-0">
                            <input class="form-check-input d-none" type="radio" name="payment_method_id" id="method{{ $method->id }}" value="{{ $method->id }}" data-type="{{ $method->type }}" required>
                            <label class="form-check-label d-flex align-items-center border rounded-3 p-3 w-100 cursor-pointer payment-label" for="method{{ $method->id }}" style="cursor: pointer;">
                                <div class="bg-light rounded p-2 me-3">
                                    <i class="bi {{ $method->type == 'qris' ? 'bi-qr-code' : 'bi-bank' }} fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $method->name }}</div>
                                    <small class="text-muted">{{ $method->type == 'qris' ? 'Otomatis Verifikasi' : 'Cek Manual Admin' }}</small>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-circle text-muted check-icon"></i>
                                    <i class="bi bi-check-circle-fill text-success check-icon-active d-none"></i>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div id="transfer-proof" class="mt-3 p-3 bg-light rounded-3" style="display: none;">
                        <label for="payment_proof" class="form-label small fw-bold">Upload Bukti Transfer</label>
                        <input type="file" class="form-control" id="payment_proof" name="proof_image" accept="image/*">
                        <div class="form-text">Format: JPG, PNG. Maks 2MB.</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold rounded-pill shadow mb-5" id="submitBtn">
                Buat Pesanan Sekarang
            </button>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                
                <div class="d-flex flex-column gap-3 mb-4">
                    @foreach($cartItems as $item)
                    <div class="d-flex align-items-center">
                        <img src="{{ $item['image_url'] }}" class="rounded-3 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 small fw-bold">{{ $item['name'] }}</h6>
                            <small class="text-muted">{{ $item['quantity'] }} x Rp {{ number_format($item['discounted_price'], 0, ',', '.') }}</small>
                        </div>
                        <div class="fw-semibold small">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal Produk</span>
                        <span class="fw-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($discount)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span><i class="bi bi-tag-fill me-1"></i> Diskon ({{ $discount->name }})</span>
                        <span class="fw-bold">- Rp {{ number_format($totalPrice - $totalPrice, 0, ',', '.') }}</span> </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="fw-bold" id="shipping-cost-display">Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <span class="fw-bold fs-5">Total Bayar</span>
                        <span class="fw-bold fs-5 text-primary" id="total-payment">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <input type="hidden" id="final-subtotal" value="{{ $totalPrice }}">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Styling Script for Payment Selection
    const paymentRadios = document.querySelectorAll('input[name="payment_method_id"]');
    const paymentLabels = document.querySelectorAll('.payment-label');
    const transferProof = document.getElementById('transfer-proof');
    const submitBtn = document.getElementById('submitBtn');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset styles
            paymentLabels.forEach(lbl => {
                lbl.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
                lbl.querySelector('.check-icon').classList.remove('d-none');
                lbl.querySelector('.check-icon-active').classList.add('d-none');
            });

            // Apply style to active
            const activeLabel = this.nextElementSibling;
            activeLabel.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
            activeLabel.querySelector('.check-icon').classList.add('d-none');
            activeLabel.querySelector('.check-icon-active').classList.remove('d-none');

            // Handle Proof Upload visibility
            if (this.dataset.type === 'transfer') {
                transferProof.style.display = 'block';
                document.getElementById('payment_proof').required = true;
            } else {
                transferProof.style.display = 'none';
                document.getElementById('payment_proof').required = false;
            }
        });
    });

    // Simple Shipping Logic (Mockup for View)
    const shippingRadios = document.querySelectorAll('.shipping-method');
    const shippingDisplay = document.getElementById('shipping-cost-display');
    const totalDisplay = document.getElementById('total-payment');
    const subtotal = parseFloat(document.getElementById('final-subtotal').value);

    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            let cost = 0;
            if(this.value === 'delivery') {
                // Here you would normally fetch from API or select option
                document.getElementById('courier-options').style.display = 'block';
                cost = 20000; // Flat rate fallback visualization
            } else {
                document.getElementById('courier-options').style.display = 'none';
                cost = 0;
            }
            
            shippingDisplay.textContent = 'Rp ' + cost.toLocaleString('id-ID');
            totalDisplay.textContent = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
        });
    });
</script>
@endsection