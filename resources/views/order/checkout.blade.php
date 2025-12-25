@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Checkout</h2>
    </div>

    <div class="col-lg-8">
        {{-- PASTIKAN TAG FORM MEMBUNGKUS SELURUH INPUT --}}
        <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
            @csrf
            
            {{-- DATA ITEM: Diletakkan tepat di bawah @csrf agar pasti terkirim --}}
            @if(isset($cartItems) && count($cartItems) > 0)
                @foreach($cartItems as $item)
                    {{-- Kita pakai name 'direct_products' untuk kedua kondisi (Cart/Direct) agar Controller mudah membaca --}}
                    <input type="hidden" name="direct_products[{{ $item['id'] }}]" value="{{ $item['quantity'] }}">
                @endforeach
            @else
                <div class="alert alert-danger">Peringatan: Tidak ada produk yang terdeteksi untuk dicheckout.</div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2 text-primary"></i>Informasi Penerima</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Penerima</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. Telepon</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ $user->phone }}" required>
                        </div>

                        <div id="delivery-fields" style="display: none;" class="row g-3 px-0 m-0">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Provinsi</label>
                                <select class="form-select" id="province_id">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Kota/Kabupaten</label>
                                <select class="form-select" id="city_id" disabled>
                                    <option value="">-- Pilih Provinsi Dulu --</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Kecamatan</label>
                                <select class="form-select" name="district_id" id="district_id" disabled>
                                    <option value="">-- Pilih Kota Dulu --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Alamat Lengkap</label>
                            <textarea name="customer_address" class="form-control" rows="2" required>{{ $user->address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-truck me-2 text-primary"></i>Metode Pengiriman</h5>
                    
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check shipping-method" name="delivery_type" id="pickup" value="pickup" checked>
                        <label class="btn btn-outline-secondary py-3" for="pickup">
                            <i class="bi bi-shop d-block fs-4 mb-1"></i> Ambil di Toko
                        </label>
                    
                        <input type="radio" class="btn-check shipping-method" name="delivery_type" id="delivery" value="delivery"> 
                        <label class="btn btn-outline-secondary py-3" for="delivery">
                            <i class="bi bi-box-seam d-block fs-4 mb-1"></i> Ekspedisi
                        </label>
                    </div>

                    <div id="courier-options" class="mt-4" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Pilih Kurir</label>
                                <select class="form-select" id="courier_code" name="courier_code">
                                    <option value="lion">Lion Parcel</option>
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Metode Pembayaran</h5>
                    @foreach($paymentMethods as $method)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method_id" id="pay{{$method->id}}" value="{{$method->id}}" required>
                        <label class="form-check-label" for="pay{{$method->id}}">
                            {{ $method->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill mb-5">
                Konfirmasi Pesanan
            </button>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Ringkasan</h5>
                @foreach($cartItems as $item)
                <div class="d-flex justify-content-between mb-2 small">
                    <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                    <span class="fw-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
                @endforeach
                <hr>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Promo / Diskon</label>
                    {{-- Atribut form="checkoutForm" vital agar select ini terbaca oleh form di kolom kiri --}}
                    <select name="discount_id" id="discount_id" form="checkoutForm" class="form-select">
                        <option value="" data-amount="0">Tanpa Diskon</option>
                        @foreach($discounts as $d)
                            <option value="{{ $d->id }}" data-type="{{ $d->type }}" data-amount="{{ $d->amount }}">
                                {{ $d->name }} ({{ $d->type == 'percentage' ? $d->amount.'%' : 'Rp '.number_format($d->amount) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between mb-2 text-danger" id="discount-row" style="display:none !important;">
                    <span>Potongan Diskon</span>
                    <span class="fw-bold" id="discount-display">- Rp 0</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Ongkir</span>
                    <span class="fw-bold" id="shipping-cost-display">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-3">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-5 text-primary" id="total-payment">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <input type="hidden" id="final-subtotal" value="{{ $totalPrice }}">
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const subtotal = parseFloat($('#final-subtotal').val());
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

        // Logic Wilayah
        $('#province_id').change(function() {
            let id = $(this).val();
            $('#city_id').html('<option>Loading...</option>').prop('disabled', true);
            if(id) {
                $.get('/rajaongkir/cities/' + id, function(data) {
                    let opts = '<option value="">-- Pilih Kota --</option>';
                    data.forEach(d => { opts += `<option value="${d.city_id || d.id}">${d.type || ''} ${d.city_name || d.name}</option>`; });
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
                    data.forEach(d => { opts += `<option value="${d.subdistrict_id || d.id}">${d.subdistrict_name || d.name}</option>`; });
                    $('#district_id').html(opts).prop('disabled', false);
                });
            }
        });

        function calculateShipping() {
            let distId = $('#district_id').val();
            let courier = $('#courier_code').val();
            if(!distId || !courier) return;
            $.ajax({
                url: "{{ route('rajaongkir.shipping') }}",
                data: { district_id: distId, courier: courier },
                success: function(response) {
                    let costs = response.costs || [];
                    $('#courier_service').empty().append('<option value="">Pilih Layanan...</option>');
                    costs.forEach(c => {
                        $('#courier_service').append(`<option value="${c.service}" data-cost="${c.cost}">${c.service} - Rp ${parseInt(c.cost).toLocaleString('id-ID')}</option>`);
                    });
                    $('#courier_service option:eq(1)').prop('selected', true).trigger('change');
                }
            });
        }

        $('#district_id, #courier_code').change(function() {
            if ($('input[name="delivery_type"]:checked').val() === 'delivery') calculateShipping();
        });

        $('#courier_service').change(function() {
            currentShipping = parseFloat($(this).find(':selected').data('cost')) || 0;
            $('#shipping-cost-display').text('Rp ' + currentShipping.toLocaleString('id-ID'));
            updateGrandTotal();
        });

        $('input[name="delivery_type"]').change(function() {
            if($(this).val() === 'delivery') {
                $('#delivery-fields, #courier-options').slideDown();
                $('#province_id, #city_id, #district_id').prop('required', true);
            } else {
                $('#delivery-fields, #courier-options').slideUp();
                $('#province_id, #city_id, #district_id').prop('required', false);
                currentShipping = 0;
                $('#shipping-cost-display').text('Rp 0');
                updateGrandTotal();
            }
        });

        $('#discount_id').change(updateGrandTotal);
    });
</script>
@endsection