@extends('layouts.app')

@section('title', 'Sewa Kebaya')

@section('content')

<div class="container pb-4">
    <h1 class="display-5 fw-normal text-uppercase text-black mb-2 text-center text-md-start" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Form Penyewaan</h1>
    <div class="d-none d-md-block" style="width: 60px; height: 1px; background-color: #000; margin-top: 15px;"></div>
</div>

<div class="container pb-5">
    <form action="{{ route('rent.store') }}" method="POST" id="rentForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="shipping_cost" id="shipping_cost_value" value="0">

        @if($product)
            <input type="hidden" name="products[0][id]" value="{{ $product->id }}">
            <input type="hidden" name="products[0][quantity]" value="1">
        @endif

        <div class="row g-5">
            <div class="col-lg-8">
                <div class="card border rounded-0 mb-5 bg-white" style="border-color: var(--border-color);">
                    <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                        <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Detail Sewa</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($product)
                            <div class="d-flex align-items-center mb-4 pb-4 border-bottom" style="border-color: #eee !important;">
                                <div class="border p-1 me-3">
                                    <img src="{{ $product->image_url }}" class="d-block" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                                <div>
                                    <h6 class="fw-bold text-uppercase text-black mb-1 small" style="letter-spacing: 0.05em;">{{ $product->name }}</h6>
                                    <p class="text-muted small mb-0">Rp {{ number_format($product->rent_price_per_day,0,',','.') }} / hari</p>
                                </div>
                            </div>
                        @endif

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ date('Y-m-d', strtotime('+3 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Nama Penerima</label>
                                <input type="text" name="customer_name" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">No. WhatsApp</label>
                                <input type="text" name="customer_phone" class="form-control rounded-0 bg-subtle border-0 p-3" value="{{ auth()->user()->phone }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border rounded-0 mb-5 bg-white" style="border-color: var(--border-color);">
                    <div class="card-header bg-white border-bottom p-4" style="border-color: var(--border-color) !important;">
                        <h5 class="fw-normal text-uppercase text-black mb-0" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Metode Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="btn-group w-100 border border-black rounded-0" role="group">
                            <input type="radio" class="btn-check delivery-type" name="delivery_type" id="pickup" value="pickup" checked>
                            <label class="btn btn-outline-custom rounded-0 py-3 text-uppercase fw-bold" for="pickup" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                <i class="bi bi-shop d-block fs-4 mb-2"></i> Ambil di Toko
                            </label>
                        
                            <input type="radio" class="btn-check delivery-type" name="delivery_type" id="delivery" value="delivery"> 
                            <label class="btn btn-outline-custom rounded-0 py-3 text-uppercase fw-bold" for="delivery" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                                <i class="bi bi-box-seam d-block fs-4 mb-2"></i> Ekspedisi
                            </label>
                        </div>

                        <div id="delivery-section" class="mt-4 pt-4 border-top" style="display:none; border-color: #eee !important;">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Provinsi</label>
                                    <select id="province_id" class="form-select rounded-0 bg-subtle border-0 p-3">
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinces as $p)
                                            <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kurir</label>
                                    <select name="courier_code" id="courier_code" class="form-select rounded-0 bg-subtle border-0 p-3">
                                        <option value="lion">Lion Parcel</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS Indonesia</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Layanan</label>
                                    <select name="courier_service" id="courier_service" class="form-select rounded-0 bg-subtle border-0 p-3">
                                        <option value="">Pilih Layanan...</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Alamat Lengkap</label>
                                    <textarea name="customer_address" class="form-control rounded-0 bg-subtle border-0 p-3" rows="3" required>{{ auth()->user()->address }}</textarea>
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
                            {{-- Tambahkan data-name untuk mendeteksi nama metode di JS --}}
                            <input class="form-check-input mt-1 payment-method-radio" 
                                   type="radio" 
                                   name="payment_method_id" 
                                   id="pay{{$method->id}}" 
                                   value="{{$method->id}}" 
                                   data-name="{{ strtolower($method->name) }}"
                                   required 
                                   style="border-color: #000;">
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

                        {{-- AREA UPLOAD BUKTI TRANSFER (Hidden by default) --}}
                        <div id="payment-proof-section" class="mt-4 pt-4 border-top" style="display: none; border-color: #eee !important;">
                            <div class="alert alert-light border rounded-0 mb-3" role="alert">
                                <small class="text-muted"><i class="bi bi-info-circle me-2"></i>Silakan transfer sesuai total tagihan, lalu unggah bukti transfer di bawah ini.</small>
                            </div>

                            <label for="payment_proof" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Bukti Transfer</label>
                            <input type="file" name="payment_proof" id="payment_proof" class="form-control rounded-0 bg-subtle border-0 p-3" accept="image/*">
                            <div class="form-text small text-muted mt-2">Format: JPG, PNG, JPEG. Maks: 2MB.</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 text-uppercase fw-bold rounded-0 mb-5" style="letter-spacing: 0.15em;">
                    Konfirmasi Sewa
                </button>
            </div>

            <div class="col-lg-4">
                <div class="card border rounded-0 bg-subtle p-4 sticky-top" style="border-color: var(--border-color); top: 100px; z-index: 1;">
                    <h5 class="fw-normal text-uppercase text-black mb-4 pb-3 border-bottom border-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Ringkasan Biaya</h5>

                    @if($product)
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0 border p-1 bg-white me-3" style="border-color: #e0e0e0;">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="d-block object-fit-cover" style="width: 60px; height: 60px;">
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="text-uppercase small fw-bold text-black mb-1 text-break" style="letter-spacing: 0.05em; line-height: 1.4;">
                                {{ $product->name }}
                            </h6>
                            <p class="mb-0" style="font-size: 0.75rem;">Rp {{ number_format($product->rent_price_per_day,0,',','.') }} / hari</p>
                        </div>
                    </div>
                    @endif
                    
                    <hr class="border-secondary opacity-25 my-4">

                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 0.1em; font-size: 0.7rem;">Kode Promo</label>
                        <select name="discount_id" id="discount_id" class="form-select rounded-0 bg-white border-0 p-2 text-uppercase small" style="font-size: 0.8rem;">
                            <option value="" data-amount="0">Tanpa Diskon</option>
                            @foreach($discounts as $d)
                                <option value="{{ $d->id }}" data-type="{{ $d->type }}" data-amount="{{ $d->amount }}">
                                    {{ $d->name }} ({{ $d->type == 'percentage' ? $d->amount.'%' : 'Rp '.number_format($d->amount) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted text-uppercase" style="letter-spacing: 0.05em;">Sewa (<span id="days-count">0</span> hari)</span>
                        <span class="fw-bold text-black" id="rent-subtotal">Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-danger small" id="discount-row" style="display:none !important;">
                        <span class="text-uppercase" style="letter-spacing: 0.05em;">Diskon</span>
                        <span class="fw-bold" id="discount-display">- Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 small">
                        <span class="text-muted text-uppercase" style="letter-spacing: 0.05em;">Ongkir</span>
                        <span class="fw-bold text-black" id="shipping-cost-display">Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between border-top border-black pt-4">
                        <span class="fw-bold fs-5 text-uppercase" style="font-family: 'Marcellus', serif;">Total</span>
                        <span class="fw-bold fs-5 text-black" id="total-payment" style="font-family: 'Marcellus', serif;">Rp 0</span>
                    </div>
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

    // Toggle Upload Bukti Transfer
    $('.payment-method-radio').on('change', function() {
        const methodName = $(this).data('name');
        
        // Logika: Jika nama metode mengandung kata "transfer", tampilkan upload
        // Sesuaikan kata kunci ini dengan nama metode di database Anda
        if (methodName.includes('transfer') || methodName.includes('bank')) {
            $('#payment-proof-section').slideDown();
            $('#payment_proof').prop('required', true); // Wajib upload jika transfer
        } else {
            $('#payment-proof-section').slideUp();
            $('#payment_proof').prop('required', false); // Tidak wajib jika bukan transfer
            $('#payment_proof').val(''); // Reset file jika ganti metode
        }
    });
</script>
@endsection