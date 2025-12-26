@extends('layouts.app')

@section('title', 'Tambah Pesanan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="fw-bold text mb-4">Tambah Pesanan Baru</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text mb-3">Informasi Pelanggan</h5>
                                
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                              id="customer_address" name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="delivery_type" class="form-label fw-bold">Metode Pengiriman</label>
                                    <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                            id="delivery_type" name="delivery_type" required>
                                        <option value="pickup" {{ old('delivery_type') == 'pickup' ? 'selected' : '' }}>Ambil di Tempat</option>
                                        <option value="delivery" {{ old('delivery_type') == 'delivery' ? 'selected' : '' }}>Dikirim ke Alamat</option>
                                    </select>
                                </div>

                                {{-- Container RajaOngkir: Hanya muncul jika delivery dipilih --}}
                                <div id="delivery-info-container" style="display: none; border-left: 4px solid #0d6efd; padding-left: 15px; margin-bottom: 20px;">
                                    <h6 class="text-primary fw-bold mb-3">Detail Pengiriman (RajaOngkir)</h6>
                                    
                                    <div class="mb-3">
                                        <label for="province_id" class="form-label">Provinsi</label>
                                        <select name="province_id" id="province_id" class="form-select shipping-input">
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="city_id" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select shipping-input" id="city_id" name="city_id" disabled>
                                            <option value="">Pilih Kota/Kabupaten</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="district_id" class="form-label">Kecamatan</label>
                                        <select class="form-select shipping-input" id="district_id" name="district_id" disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="courier_code" class="form-label">Kurir</label>
                                        <select class="form-select shipping-input" id="courier_code" name="courier_code">
                                            <option value="jne">JNE</option>
                                            <option value="sicepat">SiCepat</option>
                                            <option value="jnt">J&T</option>
                                            <option value="tiki">TIKI</option>
                                            <option value="pos">POS</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="courier_service" class="form-label">Layanan Kurir</label>
                                        <select class="form-select shipping-input" id="courier_service" name="courier_service">
                                            <option value="">Pilih Layanan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Biaya Pengiriman</label>
                                        <input type="text" class="form-control" id="shipping_cost_display" readonly value="Rp 0">
                                        <input type="hidden" name="shipping_cost" id="shipping_cost_value" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text mb-3">Pilih Produk</h5>
                                <div id="products-container">
                                    {{-- Produk item pertama --}}
                                    <div class="product-item mb-3 p-3 border rounded">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <select class="form-select product-select" name="products[0][id]" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control quantity-input" name="products[0][quantity]" value="1" min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-product" disabled><i class="bi bi-trash"></i></button>
                                            </div>
                                        </div>
                                        <div class="mt-2"><small class="text-muted subtotal">Subtotal: Rp 0</small></div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-product">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk
                                </button>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="mb-0">Total Pembayaran: <span id="total-amount-display">Rp 0</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Buat Pesanan</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-lg">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryTypeSelect = document.getElementById('delivery_type');
    const deliveryContainer = document.getElementById('delivery-info-container');
    const shippingInputs = document.querySelectorAll('.shipping-input');
    const productsContainer = document.getElementById('products-container');
    const totalAmountDisplay = document.getElementById('total-amount-display');
    const shippingCostDisplay = document.getElementById('shipping_cost_display');
    const shippingCostValue = document.getElementById('shipping_cost_value');
    let productCount = 1;

    // --- LOGIKA TOGGLE TAMPILAN ---
    function toggleDeliveryFields() {
        if (deliveryTypeSelect.value === 'delivery') {
            $(deliveryContainer).slideDown(); // Menggunakan slide agar smooth
            shippingInputs.forEach(input => input.required = true);
        } else {
            $(deliveryContainer).slideUp();
            shippingInputs.forEach(input => {
                input.required = false;
                input.value = "";
            });
            shippingCostDisplay.value = "Rp 0";
            shippingCostValue.value = "0";
            calculateTotal();
        }
    }

    deliveryTypeSelect.addEventListener('change', toggleDeliveryFields);
    toggleDeliveryFields(); // Jalankan saat load pertama kali

    // --- LOGIKA PERHITUNGAN TOTAL ---
    function calculateTotal() {
        let productTotal = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const select = item.querySelector('.product-select');
            const qtyInput = item.querySelector('.quantity-input');
            const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
            const qty = parseInt(qtyInput.value) || 0;
            productTotal += price * qty;
        });

        const shippingCost = parseInt(shippingCostValue.value) || 0;
        const grandTotal = productTotal + shippingCost;
        totalAmountDisplay.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // --- LOGIKA PRODUK ---
    function updateSubtotal(item) {
        const select = item.querySelector('.product-select');
        const qtyInput = item.querySelector('.quantity-input');
        const subtotalEl = item.querySelector('.subtotal');
        const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
        const qty = parseInt(qtyInput.value) || 0;
        subtotalEl.textContent = 'Subtotal: Rp ' + (price * qty).toLocaleString('id-ID');
    }

    document.getElementById('add-product').addEventListener('click', function() {
        const newItem = document.querySelector('.product-item').cloneNode(true);
        newItem.querySelectorAll('input, select').forEach(input => {
            input.value = input.tagName === 'INPUT' ? 1 : "";
            input.name = input.name.replace('[0]', `[${productCount}]`);
        });
        newItem.querySelector('.remove-product').disabled = false;
        newItem.querySelector('.subtotal').textContent = "Subtotal: Rp 0";
        productsContainer.appendChild(newItem);
        productCount++;
    });

    productsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            const item = e.target.closest('.product-item');
            updateSubtotal(item);
            calculateTotal();
            if (deliveryTypeSelect.value === 'delivery') calculateShipping();
        }
    });

    productsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-product')) {
            e.target.closest('.product-item').remove();
            calculateTotal();
            if (deliveryTypeSelect.value === 'delivery') calculateShipping();
        }
    });

    // --- LOGIKA RAJAONGKIR ---
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const courierSelect = document.getElementById('courier_code');
    const serviceSelect = document.getElementById('courier_service');

    provinceSelect.addEventListener('change', async function() {
        if (!this.value) return;
        citySelect.disabled = true;
        const res = await fetch(`/admin/rajaongkir/cities/${this.value}`);
        const result = await res.json();
        citySelect.innerHTML = '<option value="">Pilih Kota</option>';
        result.forEach(city => {
            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
        });
        citySelect.disabled = false;
    });

    citySelect.addEventListener('change', async function() {
        if (!this.value) return;
        districtSelect.disabled = true;
        const res = await fetch(`/admin/rajaongkir/districts/${this.value}`);
        const result = await res.json();
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        result.forEach(dist => {
            districtSelect.innerHTML += `<option value="${dist.id}">${dist.name}</option>`;
        });
        districtSelect.disabled = false;
    });

    async function calculateShipping() {
        const districtId = districtSelect.value;
        const courier = courierSelect.value;
        if (!districtId || !courier) return;

        let totalWeight = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const qty = parseInt(item.querySelector('.quantity-input').value) || 0;
            totalWeight += qty * 1000; // Asumsi per baju 1kg
        });

        serviceSelect.innerHTML = '<option>Loading...</option>';
        const res = await fetch(`/admin/rajaongkir/shipping?district_id=${districtId}&courier=${courier}&weight=${totalWeight}`);
        const result = await res.json();
        
        serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
        if (result.costs) {
            result.costs.forEach(opt => {
                serviceSelect.innerHTML += `<option value="${opt.service}" data-cost="${opt.cost}">${opt.service} (Rp ${opt.cost.toLocaleString('id-ID')})</option>`;
            });
        }
    }

    [districtSelect, courierSelect].forEach(el => el.addEventListener('change', calculateShipping));

    serviceSelect.addEventListener('change', function() {
        const cost = this.selectedOptions[0]?.dataset.cost || 0;
        shippingCostValue.value = cost;
        shippingCostDisplay.value = 'Rp ' + parseInt(cost).toLocaleString('id-ID');
        calculateTotal();
    });
});
</script>
@endsection