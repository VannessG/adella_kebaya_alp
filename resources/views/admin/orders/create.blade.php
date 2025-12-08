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
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                              id="customer_address" name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="delivery_type" class="form-label">Metode Pengiriman</label>
                                    <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                            id="delivery_type" name="delivery_type" required>
                                        <option value="pickup" {{ old('delivery_type') == 'pickup' ? 'selected' : '' }}>Ambil di Tempat</option>
                                        <option value="delivery" {{ old('delivery_type') == 'delivery' ? 'selected' : '' }}>Dikirim ke Alamat</option>
                                    </select>
                                    @error('delivery_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="province_id" class="form-label">Provinsi</label>
                                    <select name="province_id" id="province_id" class="form-select" required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">Kota/Kabupaten</label>
                                    <select class="form-select" id="city_id" name="city_id" required disabled>
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">Kecamatan</label>
                                    <select class="form-select" id="district_id" name="district_id" required disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="courier_code" class="form-label">Kurir</label>
                                    <select class="form-select" id="courier_code" name="courier_code" required>
                                        <option value="jne">JNE</option>
                                        <option value="sicepat">SiCepat</option>
                                        <option value="jnt">J&T</option>
                                        <option value="tiki">TIKI</option>
                                        <option value="pos">POS</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="courier_service" class="form-label">Layanan Kurir</label>
                                    <select class="form-select" id="courier_service" name="courier_service" required>
                                        <option value="">Pilih Layanan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Biaya Pengiriman</label>
                                    <input type="text" class="form-control" id="shipping_cost" name="shipping_cost" readonly value="Rp 0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text mb-3">Pilih Produk</h5>
                                
                                <div id="products-container">
                                    <div class="product-item mb-3 p-3 border rounded">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <select class="form-select product-select" name="products[0][id]" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" 
                                                                data-price="{{ $product->price }}"
                                                                data-stock="{{ $product->stock }}">
                                                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control quantity-input" 
                                                       name="products[0][quantity]" value="1" min="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-product" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted subtotal">Subtotal: Rp 0</small>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-product">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk
                                </button>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="mb-0">Total: <span id="total-amount">Rp 0</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-lg">
                                <i class="bi bi-check-circle"></i> Buat Pesanan
                            </button>
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
    let productCount = 1;
    const productsContainer = document.getElementById('products-container');
    const addProductBtn = document.getElementById('add-product');
    const totalAmountElement = document.getElementById('total-amount');

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const select = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const price = select.selectedOptions[0]?.dataset.price || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            total += price * quantity;
        });
        const shippingCost = parseInt(serviceSelect.selectedOptions[0]?.dataset.cost || 0);
        totalAmountElement.textContent = 'Rp ' + (total + shippingCost).toLocaleString('id-ID');
    }

    function updateSubtotal(item) {
        const select = item.querySelector('.product-select');
        const quantityInput = item.querySelector('.quantity-input');
        const subtotalElement = item.querySelector('.subtotal');
        const price = select.selectedOptions[0]?.dataset.price || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const subtotal = price * quantity;
        subtotalElement.textContent = 'Subtotal: Rp ' + subtotal.toLocaleString('id-ID');
        
        // Update max quantity based on stock
        const stock = parseInt(select.selectedOptions[0]?.dataset.stock) || 0;
        quantityInput.max = stock;
        
        // Validate quantity
        if (quantity > stock) {
            quantityInput.classList.add('is-invalid');
            quantityInput.setCustomValidity('Jumlah melebihi stok');
        } else {
            quantityInput.classList.remove('is-invalid');
            quantityInput.setCustomValidity('');
        }
    }

    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-product');
        removeButtons.forEach((btn, index) => {
            btn.disabled = removeButtons.length === 1;
        });
    }

    // Add product event
    addProductBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'product-item mb-3 p-3 border rounded';
        newItem.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-6">
                    <select class="form-select product-select" name="products[${productCount}][id]" required>
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}">
                                {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control quantity-input" 
                           name="products[${productCount}][quantity]" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-muted subtotal">Subtotal: Rp 0</small>
            </div>
        `;
        productsContainer.appendChild(newItem);
        productCount++;

        // Add event listeners to new elements
        newItem.querySelector('.product-select').addEventListener('change', function() {
            updateSubtotal(newItem);
            calculateTotal();
        });
        newItem.querySelector('.quantity-input').addEventListener('input', function() {
            updateSubtotal(newItem);
            calculateTotal();
        });
        newItem.querySelector('.remove-product').addEventListener('click', function() {
            newItem.remove();
            calculateTotal();
            updateRemoveButtons();
        });

        updateRemoveButtons();
    });

    // Event delegation for existing elements
    productsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            updateSubtotal(e.target.closest('.product-item'));
            calculateTotal();
        }
    });

    productsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateSubtotal(e.target.closest('.product-item'));
            calculateTotal();
        }
    });

    productsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
            const btn = e.target.classList.contains('remove-product') ? e.target : e.target.closest('.remove-product');
            if (!btn.disabled) {
                btn.closest('.product-item').remove();
                calculateTotal();
                updateRemoveButtons();
            }
        }
    });

    // RajaOngkir AJAX
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const courierSelect = document.getElementById('courier_code');
    const serviceSelect = document.getElementById('courier_service');
    const shippingCostInput = document.getElementById('shipping_cost');

    provinceSelect.addEventListener('change', async function() {
        const provinceId = this.value;
        const citySelect = document.getElementById('city_id');
        const districtSelect = document.getElementById('district_id');
        citySelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.disabled = true;
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        districtSelect.disabled = true;
        if (!provinceId) return;
        try {
            const response = await fetch(`/admin/rajaongkir/cities/${provinceId}`);
            const result = await response.json();
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            if (result.data) {
                result.data.forEach(city => {
                    citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                });
                citySelect.disabled = false;
            }
        } catch (error) {
            citySelect.innerHTML = '<option value="">Error loading cities</option>';
        }
    });

    document.getElementById('city_id').addEventListener('change', async function() {
        const cityId = this.value;
        const districtSelect = document.getElementById('district_id');
        districtSelect.innerHTML = '<option value="">Loading...</option>';
        districtSelect.disabled = true;
        if (!cityId) return;
        try {
            const response = await fetch(`/admin/rajaongkir/districts/${cityId}`);
            const result = await response.json();
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            if (result.data) {
                result.data.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                });
                districtSelect.disabled = false;
            }
        } catch (error) {
            districtSelect.innerHTML = '<option value="">Error loading districts</option>';
        }
    });

    document.getElementById('district_id').addEventListener('change', calculateShipping);
    document.getElementById('courier_code').addEventListener('change', calculateShipping);

    async function calculateShipping() {
        const districtId = document.getElementById('district_id').value;
        const courier = document.getElementById('courier_code').value;
        let totalWeight = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const select = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const productId = select.value;
            const quantity = parseInt(quantityInput.value) || 0;
            const product = @json($products).find(p => p.id == productId);
            if (product) {
                totalWeight += (product.weight || 0) * quantity;
            }
        });
        if (districtId && courier && totalWeight > 0) {
            const response = await fetch(`/admin/rajaongkir/shipping?district_id=${districtId}&courier=${courier}&weight=${totalWeight}`);
            const result = await response.json();
            const serviceSelect = document.getElementById('courier_service');
            serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
            if (result.costs) {
                result.costs.forEach(opt => {
                    serviceSelect.innerHTML += `<option value="${opt.service}" data-cost="${opt.cost}">${opt.name} - ${opt.service} (Rp ${opt.cost})</option>`;
                });
            }
        }
    }

    serviceSelect.addEventListener('change', function() {
        const selected = serviceSelect.selectedOptions[0];
        const cost = selected ? selected.dataset.cost : 0;
        shippingCostInput.value = 'Rp ' + parseInt(cost || 0).toLocaleString('id-ID');
        calculateTotal();
    });

    // Initialize
    updateRemoveButtons();
    calculateTotal();
});
</script>
@endsection