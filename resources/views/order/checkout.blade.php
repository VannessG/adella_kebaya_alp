@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start font-serif h3">Form Pembelian</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
        @csrf
        <input type="hidden" name="shipping_cost" id="shipping_cost_value" value="0">
    
        @if(isset($cartItems) && count($cartItems) > 0)
            @foreach($cartItems as $item)
                <input type="hidden" name="direct_products[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
            @endforeach
        @endif

        <div class="row g-5">
            <div class="col-12 col-lg-8">

                <div class="card border rounded-0 mb-4 bg-white shadow-sm">
                    <div class="card-header bg-white border-bottom p-3 p-md-4">
                        <h5 class="fw-normal text-uppercase text-black mb-0 font-serif h6">Informasi Penerima</h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Penerima</label>
                                <input type="text" name="customer_name" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. Telepon</label>
                                <input type="text" name="customer_phone" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $user->phone }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border rounded-0 mb-4 bg-white shadow-sm">
                    <div class="card-header bg-white border-bottom p-3 p-md-4">
                        <h5 class="fw-normal text-uppercase text-black mb-0 font-serif h6">Metode Pengiriman</h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="btn-group w-100 rounded-0 mb-3 gap-2" role="group" style="border: none;">
                            <input type="radio" class="btn-check delivery-type" name="delivery_type" id="pickup" value="pickup" checked>
                            <label class="btn btn-shipping-method rounded-0 py-3 text-uppercase fw-bold flex-fill" for="pickup" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                                <i class="bi bi-shop d-block fs-3 mb-2"></i> Ambil di Toko
                            </label>
                        
                            <input type="radio" class="btn-check delivery-type" name="delivery_type" id="delivery" value="delivery"> 
                            <label class="btn btn-shipping-method rounded-0 py-3 text-uppercase fw-bold flex-fill" for="delivery" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                                <i class="bi bi-box-seam d-block fs-3 mb-2"></i> Ekspedisi
                            </label>
                        </div>

                        <div id="delivery-section" class="mt-4 pt-3 border-top" style="display:none; border-color: #eee !important;">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Provinsi</label>
                                    <select id="province_id" class="form-select rounded-0 bg-subtle border-0 p-3">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinces as $p)
                                            <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kota/Kabupaten</label>
                                    <select id="city_id" name="city_id" class="form-select rounded-0 bg-subtle border-0 p-3" disabled>
                                        <option value="">-- Pilih Provinsi Dulu --</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kecamatan</label>
                                    <select name="district_id" id="district_id" class="form-select rounded-0 bg-subtle border-0 p-3" disabled>
                                        <option value="">-- Pilih Kota Dulu --</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kurir</label>
                                    <select name="courier_code" id="courier_code" class="form-select rounded-0 bg-subtle border-0 p-3">
                                        <option value="lion">Lion Parcel</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS Indonesia</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Layanan</label>
                                    <select name="courier_service" id="courier_service" class="form-select rounded-0 bg-subtle border-0 p-3">
                                        <option value="">Pilih Layanan...</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Alamat Lengkap</label>
                                    <textarea name="customer_address" class="form-control rounded-0 bg-subtle border-0 p-3" rows="3" required>{{ $user->address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border rounded-0 mb-4 bg-white shadow-sm">
                    <div class="card-header bg-white border-bottom p-3 p-md-4">
                        <h5 class="fw-normal text-uppercase text-black mb-0 font-serif h6">Metode Pembayaran</h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        @foreach($paymentMethods as $method)
                        <div class="form-check mb-2 p-3 border rounded-0 cursor-pointer hover-bg-subtle" style="border-color: #eee;">
                            <input class="form-check-input mt-1 payment-method-radio" type="radio" name="payment_method_id" id="pay{{$method->id}}" 
                            value="{{$method->id}}" data-name="{{ strtolower($method->name) }}"required style="border-color: #000;">
                            <label class="form-check-label ms-2 w-100 cursor-pointer" for="pay{{$method->id}}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-uppercase small text-black" style="letter-spacing: 0.05em;">{{ $method->name }}</span>
                                    @if($method->description)
                                        <small class="text-muted ms-2">{{ $method->description }}</small>
                                    @endif
                                </div>                            
                            </label>
                        </div>
                        @endforeach
                        
                        <div id="payment-proof-section" class="mt-4 pt-3 border-top" style="display: none; border-color: #eee !important;">
                            <div class="alert alert-light border rounded-0 mb-3" role="alert">
                                <small class="text-muted"><i class="bi bi-info-circle me-2"></i>Silakan transfer sesuai total tagihan, lalu unggah bukti transfer di bawah ini.</small>
                            </div>
                            <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Bukti Transfer</label>
                            <input type="file" name="payment_proof" id="payment_proof" class="form-control rounded-0 bg-subtle border-0 p-3" accept="image/*">
                            <div class="form-text small text-muted mt-2">Format: JPG, PNG, JPEG. Maks: 2MB.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border rounded-0 bg-subtle p-4 sticky-top" style="top: 100px; z-index: 1;">
                    <h5 class="fw-normal text-uppercase text-black mb-4 pb-3 border-bottom border-black font-serif h6">Ringkasan</h5>
                    
                    @foreach($cartItems as $item)
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0 border p-1 bg-white me-3" style="border-color: #e0e0e0; height: fit-content;">
                            <img src="{{ $item['image_url'] ?? $item['image'] }}" alt="{{ $item['name'] }}" class="d-block object-fit-cover" style="width: 60px; height: 60px;">
                        </div>

                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="text-uppercase small fw-bold text-black mb-0 text-break pe-2" style="letter-spacing: 0.05em; line-height: 1.4;">{{ $item['name'] }}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0 text-muted" style="font-size: 0.75rem;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                <p class="mb-0 fw-bold" style="font-size: 0.75rem;">Qty: {{ $item['quantity'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <hr class="border-secondary opacity-25 my-4">

                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kode Promo</label>
                        <select name="discount_id" id="discount_id" form="checkoutForm" class="form-select rounded-0 bg-white border-0 p-2 text-uppercase small" style="font-size: 0.8rem;">
                            <option value="" data-amount="0">Tanpa Diskon</option>
                            @foreach($discounts as $d)
                                <option value="{{ $d->id }}" data-type="{{ $d->type }}" data-amount="{{ $d->amount }}">
                                    {{ $d->name }} ({{ $d->type == 'percentage' ? $d->amount.'%' : 'Rp '.number_format($d->amount) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted text-uppercase" style="letter-spacing: 0.05em;">Subtotal</span>
                        <span class="fw-bold text-black">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-danger small" id="discount-row" style="display:none !important;">
                        <span class="text-uppercase" style="letter-spacing: 0.05em;">Diskon</span>
                        <span class="fw-bold" id="discount-display">- Rp 0</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 small">
                        <span class="text-muted text-uppercase" style="letter-spacing: 0.05em;">Ongkir</span>
                        <span class="fw-bold text-black" id="shipping-cost-display">Rp 0</span>
                    </div>
                    
                    <div class="d-flex justify-content-between border-top border-black pt-4 mb-4">
                        <span class="fw-bold fs-5 text-uppercase font-serif">Total</span>
                        <span class="fw-bold fs-5 text-black font-serif" id="total-payment">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    
                    <input type="hidden" id="final-subtotal" value="{{ $totalPrice }}">

                    <button type="submit" form="checkoutForm" class="btn btn-primary-custom w-100 py-3 text-uppercase fw-bold rounded-0" style="letter-spacing: 0.15em;">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    const subtotal = parseFloat($('#final-subtotal').val()) || 0;
    let currentShipping = 0;

    function updateGrandTotal() {
        const selectedOpt = $('#discount_id option:selected');
        const discType = selectedOpt.data('type');
        const discValue = parseFloat(selectedOpt.data('amount')) || 0;
        
        let potong = 0;
        if (discType === 'percentage') {
            potong = subtotal * (discValue / 100);
        } else {
            potong = discValue;
        }

        if (potong > 0) {
            $('#discount-row').attr('style', 'display: flex !important');
            $('#discount-display').text('- Rp ' + Math.round(potong).toLocaleString('id-ID'));
        } else {
            $('#discount-row').attr('style', 'display: none !important');
        }

        const grandTotal = (subtotal + currentShipping) - potong;
        $('#total-payment').text('Rp ' + Math.max(0, Math.round(grandTotal)).toLocaleString('id-ID'));
    }

    $('.delivery-type').change(function() {
        if(this.value === 'delivery') {
            $('#delivery-section').slideDown();
            calculateShipping(); 
        } else {
            $('#delivery-section').slideUp();
            currentShipping = 0;
            $('#shipping_cost_value').val(0);
            $('#shipping-cost-display').text('Rp 0');
            updateGrandTotal();
        }
    });

    $('#province_id').on('change', function() {
        let id = $(this).val();
        $('#city_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $('#district_id').html('<option value="">-- Pilih Kota Dulu --</option>').prop('disabled', true);
        
        if(id) {
            $.get('/rajaongkir/cities/' + id)
                .done(function(data) {
                    let opts = '<option value="">-- Pilih Kota --</option>';
                    data.forEach(d => {
                        let cid = d.city_id || d.id;
                        opts += `<option value="${cid}">${d.type || ''} ${d.city_name || d.name}</option>`;
                    });
                    $('#city_id').html(opts).prop('disabled', false);
                });
        }
    });

    $('#city_id').on('change', function() {
        let id = $(this).val();
        $('#district_id').html('<option value="">Loading...</option>').prop('disabled', true);
        if(id) {
            $.get('/rajaongkir/districts/' + id)
                .done(function(data) {
                    let opts = '<option value="">-- Pilih Kecamatan --</option>';
                    data.forEach(d => {
                        let did = d.subdistrict_id || d.id;
                        opts += `<option value="${did}">${d.subdistrict_name || d.name}</option>`;
                    });
                    $('#district_id').html(opts).prop('disabled', false);
                });
        }
    });

    function calculateShipping() {
        let distId = $('#district_id').val();
        let cityId = $('#city_id').val();
        let courier = $('#courier_code').val();

        if(!distId || distId === "" || !cityId || !courier) {
            return; 
        }

        $('#courier_service').html('<option value="">Memuat layanan...</option>');

        $.ajax({
            url: "{{ route('rajaongkir.shipping') }}",
            method: "GET",
            data: { district_id: distId, city_id: cityId, courier: courier, weight: 1000 },
            success: function(response) {
                let html = '<option value="">-- Pilih Layanan --</option>';
                if(response.costs && response.costs.length > 0) {
                    response.costs.forEach(function(c) {
                        let price = c.cost ? parseInt(c.cost).toLocaleString('id-ID') : '0';
                        html += `<option value="${c.service}" data-cost="${c.cost}">${c.service} - Rp ${price}</option>`;
                    });
                    $('#courier_service').html(html);
                    
                    let autoSelect = false;
                    $("#courier_service option").each(function() {
                        if($(this).val() === "REG" || $(this).val() === "CTC") {
                            $(this).prop('selected', true);
                            autoSelect = true;
                            return false; 
                        }
                    });
                    
                    if(!autoSelect) $('#courier_service option:eq(1)').prop('selected', true);
                    
                    $('#courier_service').trigger('change');
                } else {
                    $('#courier_service').html('<option value="">Layanan tidak tersedia</option>');
                }
            },
            error: function() {
                $('#courier_service').html('<option value="">Gagal memuat layanan</option>');
            }
        });
    }

    $('.payment-method-radio').on('change', function() {
        const methodName = $(this).data('name');
        if (methodName.includes('transfer') || methodName.includes('bank')) {
            $('#payment-proof-section').slideDown();
            $('#payment_proof').prop('required', true);
        } else {
            $('#payment-proof-section').slideUp();
            $('#payment_proof').prop('required', false);
            $('#payment_proof').val('');
        }
    });

    $('#district_id, #courier_code').on('change', function() {
        if ($('input[name="delivery_type"]:checked').val() === 'delivery') {
            calculateShipping();
        }
    });

    $('#courier_service').on('change', function() {
        currentShipping = parseFloat($(this).find(':selected').data('cost')) || 0;
        $('#shipping_cost_value').val(currentShipping);
        $('#shipping-cost-display').text('Rp ' + currentShipping.toLocaleString('id-ID'));
        updateGrandTotal();
    });

    $('#discount_id').on('change', updateGrandTotal);
});
</script>
@endsection