@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Form Pembelian</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                @csrf
            
                @if(isset($cartItems) && count($cartItems) > 0)
                    @foreach($cartItems as $item)
                        <input type="hidden" name="direct_products[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                    @endforeach
                @endif

                <div class="card border rounded-0 mb-5 bg-white" style="border-color: var(--border-color);">
                    <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                        <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Informasi Penerima</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Penerima</label>
                                <input type="text" name="customer_name" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. Telepon</label>
                                <input type="text" name="customer_phone" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ $user->phone }}" required>
                            </div>

                            {{-- Bagian Wilayah (Hidden by default) --}}
                            <div id="delivery-fields" style="display: none;" class="col-12">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Provinsi</label>
                                        <select class="form-select rounded-0 bg-subtle border-0 p-3" id="province_id">
                                            <option value="">-- Pilih Provinsi --</option>
                                            @foreach($provinces as $prov)
                                                <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kota/Kabupaten</label>
                                        <select class="form-select rounded-0 bg-subtle border-0 p-3" name="city_id" id="city_id" disabled>
                                            <option value="">-- Pilih Provinsi Dulu --</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kecamatan</label>
                                        <select class="form-select rounded-0 bg-subtle border-0 p-3" name="district_id" id="district_id" disabled>
                                            <option value="">-- Pilih Kota Dulu --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Alamat Lengkap</label>
                                <textarea name="customer_address" class="form-control rounded-0 bg-subtle border-0 p-3" rows="3" required>{{ $user->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. METODE PENGIRIMAN --}}
                <div class="card border rounded-0 mb-5 bg-white" style="border-color: var(--border-color);">
                    <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                        <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Metode Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="btn-group w-100 border border-black rounded-0" role="group">
                            <input type="radio" class="btn-check shipping-method" name="delivery_type" id="pickup" value="pickup" checked>
                            <label class="btn btn-outline-custom rounded-0 py-3 text-uppercase fw-bold" for="pickup" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                <i class="bi bi-shop d-block fs-4 mb-2"></i> Ambil di Toko
                            </label>
                        
                            <input type="radio" class="btn-check shipping-method" name="delivery_type" id="delivery" value="delivery"> 
                            <label class="btn btn-outline-custom rounded-0 py-3 text-uppercase fw-bold" for="delivery" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                <i class="bi bi-box-seam d-block fs-4 mb-2"></i> Ekspedisi
                            </label>
                        </div>

                        {{-- Opsi Kurir (Hidden by default) --}}
                        <div id="courier-options" class="mt-4 pt-4 border-top" style="display: none; border-color: #eee !important;">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Pilih Kurir</label>
                                    <select class="form-select rounded-0 bg-subtle border-0 p-3" id="courier_code" name="courier_code">
                                        <option value="lion">Lion Parcel</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS Indonesia</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Layanan</label>
                                    <select class="form-select rounded-0 bg-subtle border-0 p-3" id="courier_service" name="courier_service">
                                        <option value="">Pilih Layanan...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. METODE PEMBAYARAN --}}
                <div class="card border rounded-0 mb-5 bg-white" style="border-color: var(--border-color);">
                    <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                        <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Metode Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($paymentMethods as $method)
                        <div class="form-check mb-3 p-3 border rounded-0 cursor-pointer hover-bg-subtle" style="border-color: #eee;">
                            <input class="form-check-input mt-1" type="radio" name="payment_method_id" id="pay{{$method->id}}" value="{{$method->id}}" required style="border-color: #000;">
                            <label class="form-check-label ms-2 w-100 cursor-pointer" for="pay{{$method->id}}">
                                <span class="fw-bold text-uppercase small text-black" style="letter-spacing: 0.05em;">{{ $method->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 text-uppercase fw-bold rounded-0 mb-5" style="letter-spacing: 0.15em;">
                    Konfirmasi Pesanan
                </button>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card border rounded-0 bg-subtle p-4 sticky-top" style="border-color: var(--border-color); top: 100px; z-index: 1;">
                <h5 class="fw-normal text-uppercase text-black mb-4 pb-3 border-bottom border-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Ringkasan</h5>
                @foreach($cartItems as $item)
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 border p-1 bg-white me-3" style="border-color: #e0e0e0;">
                        <img src="{{ $item['image_url'] ?? $item['image'] }}" alt="{{ $item['name'] }}" class="d-block object-fit-cover" style="width: 60px; height: 60px;">
                    </div>

                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="text-uppercase small fw-bold text-black text-truncate pe-2" style="letter-spacing: 0.05em; max-width: 140px;" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                            <span class="text-black small fw-bold text-nowrap">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>

                        <div class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">Qty: {{ $item['quantity'] }}</div>
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

                <div class="d-flex justify-content-between mb-2 text-danger" id="discount-row" style="display:none !important;">
                    <span class="small text-uppercase" style="letter-spacing: 0.05em;">Diskon</span>
                    <span class="fw-bold" id="discount-display">- Rp 0</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small text-uppercase" style="letter-spacing: 0.05em;">Subtotal</span>
                    <span class="fw-bold text-black">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted small text-uppercase" style="letter-spacing: 0.05em;">Ongkir</span>
                    <span class="fw-bold text-black" id="shipping-cost-display">Rp 0</span>
                </div>
                
                <div class="d-flex justify-content-between border-top border-black pt-4">
                    <span class="fw-bold fs-5 text-uppercase" style="font-family: 'Marcellus', serif;">Total</span>
                    <span class="fw-bold fs-5 text-black" id="total-payment" style="font-family: 'Marcellus', serif;">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                
                <input type="hidden" id="final-subtotal" value="{{ $totalPrice }}">
            </div>
        </div>
    </div>
</div>

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

    // 1. Pilih Provinsi -> Kota
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
                })
                .fail(function(xhr) {
                    alert("Gagal memuat kota. Status: " + xhr.status);
                });
        }
    });

    // 2. Pilih Kota -> Kecamatan
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
                })
                .fail(function(xhr) {
                    alert("Gagal memuat kecamatan. Status: " + xhr.status);
                });
        }
    });

    // 3. Fungsi Hitung Ongkir
    function calculateShipping() {
        let distId = $('#district_id').val();
        let cityId = $('#city_id').val();
        let courier = $('#courier_code').val();

        if(!distId || distId === "" || distId === "Loading..." || !cityId || !courier) {
            return; 
        }

        $('#courier_service').html('<option value="">Memuat layanan...</option>');

        $.ajax({
            url: "{{ route('rajaongkir.shipping') }}",
            method: "GET",
            data: { district_id: distId, city_id: cityId, courier: courier },
            success: function(response) {
                let html = '<option value="">-- Pilih Layanan --</option>';
                if(response.costs && response.costs.length > 0) {
                    response.costs.forEach(function(c) {
                        let price = c.cost ? parseInt(c.cost).toLocaleString('id-ID') : '0';
                        html += `<option value="${c.service}" data-cost="${c.cost}">${c.service} - Rp ${price}</option>`;
                    });
                    $('#courier_service').html(html);
                    
                    let foundSameDay = false;
                    $("#courier_service option").each(function() {
                        if($(this).val() === "SAME DAY") {
                            $(this).prop('selected', true);
                            foundSameDay = true;
                        }
                    });

                    if(!foundSameDay) $('#courier_service option:eq(1)').prop('selected', true);
                    $('#courier_service').trigger('change');
                } else {
                    $('#courier_service').html('<option value="">Layanan tidak tersedia</option>');
                }
            },
            error: function(xhr) {
                alert("Error Ongkir (" + xhr.status + "): Silakan periksa terminal PHP untuk detail RajaOngkir.");
                $('#courier_service').html('<option value="">Error memuat layanan</option>');
            }
        });
    }

    // 4. Event Listeners
    $('#district_id, #courier_code').on('change', function() {
        if ($('input[name="delivery_type"]:checked').val() === 'delivery') {
            calculateShipping();
        }
    });

    $('#courier_service').on('change', function() {
        currentShipping = parseFloat($(this).find(':selected').data('cost')) || 0;
        $('#shipping-cost-display').text('Rp ' + currentShipping.toLocaleString('id-ID'));
        updateGrandTotal();
    });

    $('input[name="delivery_type"]').on('change', function() {
        if($(this).val() === 'delivery') {
            $('#delivery-fields, #courier-options').slideDown();
            calculateShipping();
        } else {
            $('#delivery-fields, #courier-options').slideUp();
            currentShipping = 0;
            $('#shipping-cost-display').text('Rp 0');
            updateGrandTotal();
        }
    });

    $('#discount_id').on('change', updateGrandTotal);
});
</script>
@endsection