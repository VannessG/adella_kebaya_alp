@extends('layouts.app')

@section('title', 'Sewa Kebaya')

@section('content')
<div class="container py-4">
    <form action="{{ route('rent.store') }}" method="POST" id="rentForm">
        @csrf
        <input type="hidden" name="shipping_cost" id="shipping_cost_value" value="0">
        
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card p-4 rounded-4 shadow-sm border-0 mb-4">
                    <h5 class="fw-bold mb-3" style="font-family: 'Playfair Display';"><i class="bi bi-calendar-check me-2 text-primary"></i>Detail Sewa</h5>
                    
                    @if($product)
                        <div class="d-flex align-items-center p-3 border rounded-4 mb-4 bg-light">
                            <img src="{{ $product->image_url }}" class="rounded-3 me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                <p class="text-primary fw-bold mb-0">Rp {{ number_format($product->rent_price_per_day,0,',','.') }}/hari</p>
                                <input type="hidden" name="products[0][id]" value="{{ $product->id }}">
                                <input type="hidden" name="products[0][quantity]" value="1">
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Penerima</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. WhatsApp</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                        </div>
                    </div>
                </div>

                <div class="card p-4 rounded-4 shadow-sm border-0">
                    <h5 class="fw-bold mb-4"><i class="bi bi-truck me-2 text-primary"></i>Pengiriman</h5>
                    
                    <div class="btn-group w-100 mb-4" role="group">
                        <input type="radio" class="btn-check delivery-type" name="delivery_type" id="pickup" value="pickup" checked>
                        <label class="btn btn-outline-secondary py-3" for="pickup"><i class="bi bi-shop d-block fs-4 mb-1"></i> Ambil di Toko</label>
                    
                        <input type="radio" class="btn-check delivery-type" name="delivery_type" id="delivery" value="delivery">
                        <label class="btn btn-outline-secondary py-3" for="delivery"><i class="bi bi-box-seam d-block fs-4 mb-1"></i> Ekspedisi</label>
                    </div>

                    <div id="delivery-section" style="display:none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Provinsi</label>
                                <select id="province_id" class="form-select">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Kota/Kabupaten</label>
                                <select id="city_id" name="city_id" class="form-select" disabled><option value="">-- Pilih Provinsi Dulu --</option></select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Kecamatan</label>
                                <select name="district_id" id="district_id" class="form-select" disabled><option value="">-- Pilih Kota Dulu --</option></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Kurir</label>
                                <select name="courier_code" id="courier_code" class="form-select">
                                    <option value="lion">Lion Parcel</option>
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Layanan</label>
                                <select name="courier_service" id="courier_service" class="form-select"><option value="">Pilih Layanan...</option></select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Alamat Lengkap</label>
                                <textarea name="customer_address" class="form-control" rows="3" required>{{ auth()->user()->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card p-4 rounded-4 shadow-sm border-0 sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4">Ringkasan Biaya</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Promo / Diskon</label>
                        <select name="discount_id" id="discount_id" class="form-select rounded-pill">
                            <option value="" data-amount="0">Tanpa Diskon</option>
                            @foreach($discounts as $d)
                                <option value="{{ $d->id }}" data-type="{{ $d->type }}" data-amount="{{ $d->amount }}">
                                    {{ $d->name }} ({{ $d->type == 'percentage' ? $d->amount.'%' : 'Rp '.number_format($d->amount) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sewa (<span id="days-count">0</span> hari)</span>
                        <span id="rent-subtotal" class="fw-bold text-dark">Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-danger" id="discount-row" style="display:none !important;">
                        <span>Potongan Diskon</span>
                        <span class="fw-bold" id="discount-display">- Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-cost-display" class="fw-bold text-dark">Rp 0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total Bayar</span>
                        <span class="fw-bold fs-4 text-primary" id="total-payment">Rp 0</span>
                    </div>

                    <h6 class="fw-bold mb-3 small uppercase text-muted">Metode Pembayaran</h6>
                    <div class="mb-4">
                        @foreach($paymentMethods as $m)
                            <div class="form-check p-3 border rounded-3 mb-2">
                                <input class="form-check-input" type="radio" name="payment_method_id" id="pay{{$m->id}}" value="{{ $m->id }}" required>
                                <label class="form-check-label w-100 fw-semibold" for="pay{{$m->id}}">{{ $m->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">Konfirmasi Sewa</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const subtotalPerDay = {{ $product ? $product->rent_price_per_day : 0 }};
    let currentShipping = 0;

    function calculateAll() {
        const start = new Date($('#start_date').val());
        const end = new Date($('#end_date').val());
        let days = 0;
        if (start && end && end > start) {
            days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        }
        $('#days-count').text(days);

        const rentTotal = subtotalPerDay * days;
        $('#rent-subtotal').text('Rp ' + rentTotal.toLocaleString('id-ID'));
        
        const selectedOpt = $('#discount_id option:selected');
        const discType = selectedOpt.data('type');
        const discValue = parseFloat(selectedOpt.data('amount')) || 0;
        
        let potong = 0;
        if (discType === 'percentage') {
            potong = rentTotal * (discValue / 100);
        } else {
            potong = discValue;
        }

        if (potong > 0) {
            $('#discount-row').attr('style', 'display: flex !important');
            $('#discount-display').text('- Rp ' + Math.round(potong).toLocaleString('id-ID'));
        } else {
            $('#discount-row').attr('style', 'display: none !important');
        }

        const finalTotal = (rentTotal + currentShipping) - potong;
        $('#total-payment').text('Rp ' + Math.max(0, Math.round(finalTotal)).toLocaleString('id-ID'));
    }

    $('.delivery-type').change(function() {
        if(this.value === 'delivery') {
            $('#delivery-section').slideDown();
            calculateShipping();
        } else {
            $('#delivery-section').slideUp();
            currentShipping = 0;
            $('#shipping_cost_value').val(0); // Reset input hidden
            $('#shipping-cost-display').text('Rp 0');
            calculateAll();
        }
    });

    $('#province_id').change(function() {
        let id = $(this).val();
        $('#city_id').html('<option>Loading...</option>').prop('disabled', true);
        $('#district_id').html('<option value="">-- Pilih Kota Dulu --</option>').prop('disabled', true);
        if(id) {
            $.get('/rajaongkir/cities/' + id, function(data) {
                let opts = '<option value="">-- Pilih Kota --</option>';
                data.forEach(d => {
                    opts += `<option value="${d.city_id || d.id}">${d.type || ''} ${d.city_name || d.name}</option>`;
                });
                $('#city_id').html(opts).prop('disabled', false);
            });
        }
    });

    $('#city_id').change(function() {
        let id = $(this).val();
        $('#district_id').html('<option>Loading...</option>').prop('disabled', true);
        if(id) {
            $.get('/rajaongkir/districts/' + id, function(data) {
                let opts = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(d => {
                    opts += `<option value="${d.subdistrict_id || d.id}">${d.subdistrict_name || d.name}</option>`;
                });
                $('#district_id').html(opts).prop('disabled', false);
            });
            calculateShipping();
        }
    });

    function calculateShipping() {
        let distId = $('#district_id').val();
        let cityId = $('#city_id').val();
        let courier = $('#courier_code').val();
        
        if(!distId || !cityId) return;

        $('#courier_service').html('<option>Loading...</option>');
        $.ajax({
            url: "{{ route('rajaongkir.shipping') }}",
            data: { district_id: distId, city_id: cityId, courier: courier, weight: 1000 },
            success: function(response) {
                let html = '<option value="">-- Pilih Layanan --</option>';
                if(response.costs && response.costs.length > 0) {
                    response.costs.forEach(function(c) {
                        html += `<option value="${c.service}" data-cost="${c.cost}">${c.service} - Rp ${parseInt(c.cost).toLocaleString('id-ID')}</option>`;
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
            }
        });
    }

    $('#district_id, #courier_code').change(calculateShipping);

    $('#courier_service').change(function() {
        currentShipping = parseInt($(this).find(':selected').data('cost')) || 0;
        $('#shipping_cost_value').val(currentShipping);
        $('#shipping-cost-display').text('Rp ' + currentShipping.toLocaleString('id-ID'));
        calculateAll();
    });

    $('#start_date, #end_date, #discount_id').change(calculateAll);
    $(document).ready(calculateAll);
</script>
@endsection