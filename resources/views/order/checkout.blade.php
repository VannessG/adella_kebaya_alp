@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="fw-bold text mb-4">Checkout Pesanan</h1>
            
            @if($discount)
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-percent display-4 me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">🎉 DISKON AKTIF!</h4>
                        <p class="mb-1">
                            <strong>{{ $discount->name }}</strong> - 
                            @if($discount->type === 'percentage')
                                {{ $discount->amount }}% OFF
                            @else
                                Rp {{ number_format($discount->amount, 0, ',', '.') }} OFF
                            @endif
                        </p>
                        <small class="text-muted">
                            Berlaku hingga: {{ $discount->end_date->format('d M Y') }}
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Detail Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Harga Satuan</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = 0;
                                            $discountAmount = 0;
                                        @endphp
                                        
                                        @foreach($cartItems as $item)
                                            @php
                                                // Hitung harga dengan diskon
                                                $originalPrice = $item['price'];
                                                $itemDiscountedPrice = $item['discounted_price'] ?? $item['price'];
                                                $itemSubtotal = $itemDiscountedPrice * $item['quantity'];
                                                $itemDiscount = ($originalPrice - $itemDiscountedPrice) * $item['quantity'];
                                                
                                                $subtotal += $itemSubtotal;
                                                $discountAmount += $itemDiscount;
                                            @endphp
                                            
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item['image_url'] ?? $item['image'] }}" 
                                                             alt="{{ $item['name'] }}" 
                                                             class="rounded me-3" 
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                                                            @if($discount && $itemDiscountedPrice < $originalPrice)
                                                            <small class="text-success">
                                                                <i class="bi bi-percent"></i> Diskon diterapkan
                                                            </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item['quantity'] }}</td>
                                                <td class="text-end">
                                                    @if($discount && $itemDiscountedPrice < $originalPrice)
                                                        <div>
                                                            <small class="text-muted text-decoration-line-through">
                                                                Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                                            </small><br>
                                                            <span class="fw-semibold">
                                                                Rp {{ number_format($itemDiscountedPrice, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="fw-semibold">
                                                            Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-semibold">
                                                    Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end">Subtotal:</td>
                                            <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        
                                        @if($discount && $discountAmount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end text-success">
                                                <i class="bi bi-tag me-1"></i> Diskon {{ $discount->name }}:
                                            </td>
                                            <td class="text-end text-success fw-bold">
                                                - Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endif
                                        
                                        <tr>
                                            <td colspan="3" class="text-end">Biaya Pengiriman:</td>
                                            <td class="text-end" id="shipping-cost-display">Rp 0</td>
                                        </tr>
                                        
                                        <tr class="border-top">
                                            <td colspan="3" class="text-end fw-bold fs-5">Total Pembayaran:</td>
                                            <td class="text-end fw-bold fs-5 text" id="total-payment">
                                                Rp {{ number_format($subtotal - $discountAmount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <input type="hidden" id="subtotal-value" value="{{ $subtotal }}">
                            <input type="hidden" id="discount-value" value="{{ $discountAmount }}">
                            <input type="hidden" id="final-subtotal" value="{{ $subtotal - $discountAmount }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 fw-semibold">Informasi Pengiriman & Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                                @csrf
                                
                                @if(isset($isDirectCheckout) && $isDirectCheckout)
                                    @foreach($cartItems as $item)
                                        <input type="hidden" name="direct_products[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                                    @endforeach
                                @endif
                                
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="customer_name" class="form-control" 
                                           value="{{ $user->name }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="customer_phone" class="form-control" 
                                           value="{{ $user->phone }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="customer_address" class="form-control" rows="3" required>{{ $user->address }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Metode Pengiriman</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input shipping-method" type="radio" 
                                                   name="delivery_type" id="pickup" value="pickup" checked 
                                                   data-cost="0">
                                            <label class="form-check-label" for="pickup">
                                                Ambil di Tempat (Gratis)
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input shipping-method" type="radio" 
                                                   name="delivery_type" id="delivery" value="delivery"
                                                   data-cost="20000">
                                            <label class="form-check-label" for="delivery">
                                                Antar ke Alamat (Rp 20.000)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <div>
                                        @foreach($paymentMethods as $method)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method_id" 
                                                   id="method{{ $method->id }}" value="{{ $method->id }}" 
                                                   data-type="{{ $method->type }}" required
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            <label class="form-check-label" for="method{{ $method->id }}">
                                                <strong>{{ $method->name }}</strong>
                                                @if($method->account_number)
                                                    <br><small class="text-muted">{{ $method->account_name }}: {{ $method->account_number }}</small>
                                                @endif
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div id="transfer-proof" style="display: none;">
                                    <div class="mb-3">
                                        <label for="payment_proof" class="form-label">Upload Bukti Transfer</label>
                                        <input type="file" class="form-control" id="payment_proof" name="proof_image" accept="image/*">
                                        <div class="form-text">Upload screenshot bukti transfer Anda</div>
                                    </div>
                                </div>

                                @if($discount)
                                <div class="alert alert-success">
                                    <small>
                                        <i class="bi bi-check-circle"></i> 
                                        <strong>Diskon diterapkan!</strong> Anda hemat 
                                        @if($discount->type === 'percentage')
                                            {{ $discount->amount }}%
                                        @else
                                            Rp {{ number_format($discount->amount, 0, ',', '.') }}
                                        @endif
                                        dari total pembelian.
                                    </small>
                                </div>
                                @endif

                                <div class="alert alert-info">
                                    <small>
                                        <i class="bi bi-info-circle"></i> 
                                        Untuk <strong>QRIS</strong>: Pembayaran otomatis dikonfirmasi<br>
                                        Untuk <strong>Transfer</strong>: Upload bukti transfer untuk konfirmasi manual
                                    </small>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg" id="submitBtn">
                                        <i class="bi bi-credit-card"></i> Proses Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        @if(isset($isDirectCheckout) && $isDirectCheckout)
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        @else
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method_id"]');
    const transferProof = document.getElementById('transfer-proof');
    const submitBtn = document.getElementById('submitBtn');
    const checkoutForm = document.getElementById('checkoutForm');
    const shippingMethods = document.querySelectorAll('.shipping-method');
    const shippingCostDisplay = document.getElementById('shipping-cost-display');
    const totalPaymentDisplay = document.getElementById('total-payment');
    
    let subtotal = parseFloat(document.getElementById('final-subtotal').value) || 0;
    let shippingCost = 0;
    
    // Update total pembayaran
    function updateTotalPayment() {
        const total = subtotal + shippingCost;
        totalPaymentDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        // Update shipping cost display
        shippingCostDisplay.textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
    }
    
    // Handle shipping method change
    shippingMethods.forEach(method => {
        method.addEventListener('change', function() {
            shippingCost = parseFloat(this.dataset.cost) || 0;
            updateTotalPayment();
        });
    });
    
    // Handle payment method change
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.dataset.type === 'transfer') {
                transferProof.style.display = 'block';
                document.getElementById('payment_proof').required = true;
                submitBtn.innerHTML = '<i class="bi bi-credit-card"></i> Proses Pembayaran & Upload Bukti';
            } else {
                transferProof.style.display = 'none';
                document.getElementById('payment_proof').required = false;
                submitBtn.innerHTML = '<i class="bi bi-credit-card"></i> Proses Pembayaran';
            }
        });
    });
    
    // Set default payment method
    const firstPaymentMethod = document.querySelector('input[name="payment_method_id"]:checked');
    if (firstPaymentMethod) {
        firstPaymentMethod.dispatchEvent(new Event('change'));
    }
    
    // Set default shipping method
    const defaultShipping = document.querySelector('.shipping-method:checked');
    if (defaultShipping) {
        shippingCost = parseFloat(defaultShipping.dataset.cost) || 0;
        updateTotalPayment();
    }
    
    // Handle form submission
    checkoutForm.addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method_id"]:checked');
        if (selectedMethod && selectedMethod.dataset.type === 'transfer') {
            const proofFile = document.getElementById('payment_proof').files[0];
            if (!proofFile) {
                e.preventDefault();
                alert('Silakan upload bukti transfer terlebih dahulu.');
                return;
            }
        }
        
        // Disable button and show loading
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        
        // Re-enable button after 5 seconds if form doesn't submit
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                alert('Proses timeout. Silakan coba lagi.');
            }
        }, 5000);
    });
});
</script>
@endsection