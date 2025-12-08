@extends('layouts.app')

@section('title', 'Sewa Kebaya')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="fw-bold text mb-4">Form Penyewaan Kebaya</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('rent.store') }}" method="POST" id="rentForm">
                        @csrf

                        @if($selectedProduct)
                        <input type="hidden" name="direct_product_id" value="{{ $selectedProduct->id }}">
                        <input type="hidden" name="direct_quantity" value="1" id="direct-quantity">
                        @endif

                        <div class="row mb-4">
                            @if($selectedProduct)
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ $selectedProduct->image_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $selectedProduct->name }}"
                                         style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $selectedProduct->name }}</h5>
                                        <p class="card-text text-muted">
                                            Harga Sewa: <br>
                                            <span class="fw-bold text fs-4">
                                                Rp {{ number_format($selectedProduct->rent_price_per_day, 0, ',', '.') }}/hari
                                            </span>
                                        </p>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Jumlah</span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="quantity-input"
                                                   value="1" 
                                                   min="1" 
                                                   max="{{ $selectedProduct->stock }}"
                                                   onchange="updateDirectQuantity(this.value)">
                                            <span class="input-group-text">pcs</span>
                                        </div>
                                        <small class="text-muted">Stok tersedia: {{ $selectedProduct->stock }} pcs</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                            @else
                            <div class="col-12">
                            @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text mb-3">Informasi Penyewaan</h5>
                                        
                                        @if(auth()->user()->role === 'admin')
                                            <div class="mb-3">
                                                <label for="branch_id" class="form-label">Cabang</label>
                                                <select class="form-select @error('branch_id') is-invalid @enderror" 
                                                        id="branch_id" name="branch_id" required>
                                                    <option value="">Pilih Cabang</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" 
                                                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }} - {{ $branch->city }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @else
                                            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle"></i> 
                                                Penyewaan akan diproses di cabang <strong>{{ $branch->city }}</strong>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                                    <input type="date" 
                                                           class="form-control @error('start_date') is-invalid @enderror" 
                                                           id="start_date" 
                                                           name="start_date" 
                                                           value="{{ old('start_date', date('Y-m-d')) }}" 
                                                           min="{{ date('Y-m-d') }}" 
                                                           required>
                                                    @error('start_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                                                    <input type="date" 
                                                           class="form-control @error('end_date') is-invalid @enderror" 
                                                           id="end_date" 
                                                           name="end_date" 
                                                           value="{{ old('end_date', date('Y-m-d', strtotime('+3 days'))) }}" 
                                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                                           required>
                                                    @error('end_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Metode Pengambilan</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="delivery_type" id="pickup" value="pickup" checked
                                                           onchange="calculateTotal()">
                                                    <label class="form-check-label" for="pickup">
                                                        Ambil di Tempat
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="delivery_type" id="delivery" value="delivery"
                                                           onchange="calculateTotal()">
                                                    <label class="form-check-label" for="delivery">
                                                        Antar ke Alamat
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info">
                                            <small>
                                                <i class="bi bi-info-circle"></i> 
                                                Durasi minimal: {{ $selectedProduct ? $selectedProduct->min_rent_days : 1 }} hari
                                                @if($selectedProduct && $selectedProduct->max_rent_days)
                                                <br>Durasi maksimal: {{ $selectedProduct->max_rent_days }} hari
                                                @endif
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="text mb-3">Informasi Pelanggan</h5>
                                        
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label">Nama Lengkap</label>
                                            <input type="text" 
                                                   class="form-control @error('customer_name') is-invalid @enderror" 
                                                   id="customer_name" 
                                                   name="customer_name" 
                                                   value="{{ old('customer_name', auth()->user()->name) }}" 
                                                   required>
                                            @error('customer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label">No. Telepon</label>
                                            <input type="text" 
                                                   class="form-control @error('customer_phone') is-invalid @enderror" 
                                                   id="customer_phone" 
                                                   name="customer_phone" 
                                                   value="{{ old('customer_phone', auth()->user()->phone) }}" 
                                                   required>
                                            @error('customer_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="customer_address" class="form-label">Alamat Lengkap</label>
                                            <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                                      id="customer_address" 
                                                      name="customer_address" 
                                                      rows="3" 
                                                      oninput="calculateTotal()"
                                                      required>{{ old('customer_address', auth()->user()->address) }}</textarea>
                                            @error('customer_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!$selectedProduct)
                        <hr class="my-4">
                        <h5 class="text mb-3">Pilih Produk</h5>
                        <div id="products-container">
                            <div class="product-item mb-3 p-3 border rounded">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <select class="form-select product-select" 
                                                name="products[0][id]" 
                                                onchange="calculateTotal()"
                                                required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-rent-price="{{ $product->rent_price_per_day }}"
                                                        data-stock="{{ $product->stock }}"
                                                        data-min-days="{{ $product->min_rent_days }}"
                                                        data-max-days="{{ $product->max_rent_days }}">
                                                    {{ $product->name }} - Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}/hari
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control quantity-input" 
                                                   name="products[0][quantity]" 
                                                   value="1" 
                                                   min="1" 
                                                   onchange="calculateTotal()"
                                                   required>
                                            <span class="input-group-text">pcs</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-product" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted subtotal">Subtotal: Rp 0</small>
                                    <br>
                                    <small class="text-muted stock-info"></small>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-product">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </button>
                        @endif

                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <h5 class="mb-3">Ringkasan Biaya</h5>
                                
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal Sewa:</span>
                                        <span id="rent-subtotal">Rp 0</span>
                                    </div>
                                </div>
                                
                                @if($discount)
                                <div class="mb-2 text-success">
                                    <div class="d-flex justify-content-between">
                                        <span>Diskon {{ $discount->name }} ({{ $discount->type == 'percentage' ? $discount->amount . '%' : 'Rp ' . number_format($discount->amount, 0, ',', '.') }}):</span>
                                        <span id="discount-amount">- Rp 0</span>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Biaya Pengiriman:</span>
                                        <span id="shipping-cost">Rp 0</span>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span id="total-amount" class="text">Rp 0</span>
                                </div>
                                
                                <div class="mt-2 text-muted small">
                                    <div id="rental-duration">Durasi: 0 hari</div>
                                    @if($discount)
                                    <div>
                                        <i class="bi bi-info-circle"></i> 
                                        Diskon berlaku sampai {{ $discount->end_date->format('d M Y') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-lg">
                                <i class="bi bi-check-circle"></i> Lanjutkan ke Pembayaran
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});

function updateDirectQuantity(value) {
    document.getElementById('direct-quantity').value = value;
    calculateTotal();
}

function calculateTotal() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    const deliveryType = document.querySelector('input[name="delivery_type"]:checked')?.value || 'pickup';
    const customerAddress = document.getElementById('customer_address')?.value || '';
    
    let rentSubtotal = 0;
    let rentalDays = 0;
    
    if (startDate && endDate && endDate > startDate) {
        rentalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
        document.getElementById('rental-duration').textContent = `Durasi: ${rentalDays} hari`;
        
        // Hitung untuk direct rent (produk yang dipilih langsung dari detail)
        const directProduct = @json($selectedProduct);
        if (directProduct) {
            const quantity = parseInt(document.getElementById('quantity-input')?.value || 1);
            const rentPrice = parseFloat(directProduct.rent_price_per_day);
            rentSubtotal = rentPrice * quantity * rentalDays;
        } else {
            // Hitung untuk multiple products
            document.querySelectorAll('.product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                
                if (select && select.value) {
                    const rentPrice = parseFloat(select.options[select.selectedIndex].dataset.rentPrice) || 0;
                    const quantity = parseInt(quantityInput.value) || 0;
                    const minDays = parseInt(select.options[select.selectedIndex].dataset.minDays) || 1;
                    const maxDays = parseInt(select.options[select.selectedIndex].datasetMaxDays) || 30;
                    const stock = parseInt(select.options[select.selectedIndex].dataset.stock) || 0;
                    
                    // Validasi durasi
                    if (rentalDays < minDays) {
                        alert(`Durasi minimal untuk produk ini adalah ${minDays} hari`);
                        document.getElementById('end_date').value = '';
                        return;
                    }
                    
                    if (maxDays && rentalDays > maxDays) {
                        alert(`Durasi maksimal untuk produk ini adalah ${maxDays} hari`);
                        document.getElementById('end_date').value = '';
                        return;
                    }
                    
                    // Validasi stok
                    if (quantity > stock) {
                        alert(`Stok tidak mencukupi. Stok tersedia: ${stock} pcs`);
                        quantityInput.value = stock;
                        return;
                    }
                    
                    const subtotal = rentPrice * quantity * rentalDays;
                    rentSubtotal += subtotal;
                    
                    // Update subtotal display
                    const subtotalElement = item.querySelector('.subtotal');
                    if (subtotalElement) {
                        subtotalElement.textContent = `Subtotal: Rp ${subtotal.toLocaleString('id-ID')}`;
                    }
                    
                    // Update stock info
                    const stockInfo = item.querySelector('.stock-info');
                    if (stockInfo) {
                        stockInfo.textContent = `Stok: ${stock} pcs | Min: ${minDays} hari | Max: ${maxDays} hari`;
                    }
                }
            });
        }
    }
    
    // Hitung diskon
    let discountAmount = 0;
    const discount = @json($discount);
    if (discount && discount.is_active) {
        if (discount.type === 'percentage') {
            discountAmount = rentSubtotal * (discount.amount / 100);
        } else {
            discountAmount = Math.min(discount.amount, rentSubtotal);
        }
    }
    
    // Hitung biaya pengiriman
    let shippingCost = 0;
    if (deliveryType === 'delivery' && customerAddress) {
        // Logika sederhana untuk hitung ongkir
        if (customerAddress.toLowerCase().includes('surabaya')) {
            shippingCost = 15000;
        } else if (customerAddress.toLowerCase().includes('bojonegoro')) {
            shippingCost = 20000;
        } else {
            shippingCost = 30000;
        }
    }
    
    const totalAmount = rentSubtotal - discountAmount + shippingCost;
    
    // Update display
    document.getElementById('rent-subtotal').textContent = `Rp ${rentSubtotal.toLocaleString('id-ID')}`;
    document.getElementById('discount-amount').textContent = `- Rp ${discountAmount.toLocaleString('id-ID')}`;
    document.getElementById('shipping-cost').textContent = `Rp ${shippingCost.toLocaleString('id-ID')}`;
    document.getElementById('total-amount').textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
}

// Fungsi untuk menambah produk (hanya untuk non-direct rent)
let productCount = 1;
document.getElementById('add-product')?.addEventListener('click', function() {
    const newItem = document.createElement('div');
    newItem.className = 'product-item mb-3 p-3 border rounded';
    newItem.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-6">
                <select class="form-select product-select" 
                        name="products[${productCount}][id]" 
                        onchange="calculateTotal()"
                        required>
                    <option value="">Pilih Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-rent-price="{{ $product->rent_price_per_day }}"
                                data-stock="{{ $product->stock }}"
                                data-min-days="{{ $product->min_rent_days }}"
                                data-max-days="{{ $product->max_rent_days }}">
                            {{ $product->name }} - Rp {{ number_format($product->rent_price_per_day, 0, ',', '.') }}/hari
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="number" 
                           class="form-control quantity-input" 
                           name="products[${productCount}][quantity]" 
                           value="1" 
                           min="1" 
                           onchange="calculateTotal()"
                           required>
                    <span class="input-group-text">pcs</span>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-product" onclick="removeProduct(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <div class="mt-2">
            <small class="text-muted subtotal">Subtotal: Rp 0</small>
            <br>
            <small class="text-muted stock-info"></small>
        </div>
    `;
    document.getElementById('products-container').appendChild(newItem);
    productCount++;
    
    // Update tombol remove
    updateRemoveButtons();
});

function removeProduct(button) {
    button.closest('.product-item').remove();
    calculateTotal();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const removeButtons = document.querySelectorAll('.remove-product');
    removeButtons.forEach((btn, index) => {
        btn.disabled = removeButtons.length === 1;
    });
}

// Event listeners untuk tanggal
document.getElementById('start_date')?.addEventListener('change', calculateTotal);
document.getElementById('end_date')?.addEventListener('change', calculateTotal);
</script>
@endsection