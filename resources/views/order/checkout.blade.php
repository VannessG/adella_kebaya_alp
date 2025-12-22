@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Checkout</h2>
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
                    <h5 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2 text-primary"></i>Alamat Pengiriman</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Penerima</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. Telepon</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ $user->phone }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Provinsi</label>
                            <select class="form-select" id="province_id" required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kota/Kabupaten</label>
                            <select class="form-select" id="city_id" disabled required>
                                <option value="">-- Pilih Provinsi Dulu --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Kecamatan</label>
                            <select class="form-select" name="district_id" id="district_id" disabled required>
                                <option value="">-- Pilih Kota Dulu --</option>
                            </select>
                            <div class="form-text text-primary">*Ongkir dihitung berdasarkan Kecamatan ini.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Alamat Lengkap (Jalan, RT/RW, No. Rumah)</label>
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
                        <div class="alert alert-info mt-3 mb-0 small">
                            <i class="bi bi-info-circle me-1"></i>
                            Barang dikirim dari cabang: <strong>{{ $branch->name }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Pembayaran</h5>
                    @foreach($paymentMethods as $method)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method_id" id="pay{{$method->id}}" value="{{$method->id}}" required>
                        <label class="form-check-label" for="pay{{$method->id}}">
                            {{ $method->name }} <small class="text-muted">({{ $method->type }})</small>
                        </label>
                    </div>
                    @endforeach
                    <div id="transfer-proof" class="mt-3" style="display:none;">
                        <label class="form-label small fw-bold">Bukti Transfer</label>
                        <input type="file" name="proof_image" class="form-control">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill mb-5" id="submitBtn">
                Buat Pesanan
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

        // --- 1. LOGIC WILAYAH ---

        // Saat Provinsi Berubah
        $('#province_id').change(function() {
            let id = $(this).val();
            
            // Reset Dropdown Kota & Kecamatan
            $('#city_id').html('<option>Loading...</option>').prop('disabled', true);
            $('#district_id').html('<option>-- Pilih Kota Dulu --</option>').prop('disabled', true);
            
            if(id) {
                // Panggil API Kota
                $.get('/rajaongkir/cities/' + id, function(data) {
                    // Cek apakah data kosong
                    if (!data || data.length === 0) {
                        $('#city_id').html('<option value="">Data Kota Kosong (Cek Log)</option>');
                        return;
                    }

                    let opts = '<option value="">-- Pilih Kota --</option>';
                    data.forEach(d => {
                        // [FIX] Handle perbedaan nama field (city_name vs name)
                        // [FIX] Handle perbedaan type (Kabupaten/Kota)
                        let type = d.type || ''; 
                        let name = d.city_name || d.name || 'Unknown'; 
                        
                        opts += `<option value="${d.city_id || d.id}">${type} ${name}</option>`;
                    });
                    $('#city_id').html(opts).prop('disabled', false);
                })
                .fail(function() {
                    $('#city_id').html('<option value="">Gagal memuat kota</option>');
                });
            }
        });

        // Saat Kota Berubah
        $('#city_id').change(function() {
            let id = $(this).val();
            $('#district_id').html('<option>Loading...</option>').prop('disabled', true);
            
            if(id) {
                // Panggil API Kecamatan
                $.get('/rajaongkir/districts/' + id, function(data) {
                    if (!data || data.length === 0) {
                        $('#district_id').html('<option value="">Data Kec. Kosong</option>');
                        return;
                    }

                    let opts = '<option value="">-- Pilih Kecamatan --</option>';
                    data.forEach(d => {
                        // [FIX] Kecamatan biasanya pakai subdistrict_name atau name
                        let name = d.subdistrict_name || d.name || 'Unknown';
                        opts += `<option value="${d.subdistrict_id || d.id}">${name}</option>`;
                    });
                    $('#district_id').html(opts).prop('disabled', false);
                });
            }
        });

        // --- 2. LOGIC ONGKIR ---
        
        function calculateShipping() {
            let distId = $('#district_id').val();
            let courier = $('#courier_code').val();
            
            // Jangan hitung kalau belum pilih kecamatan
            if(!distId || !courier) return;

            $('#courier_service').html('<option>Loading...</option>');
            $('#shipping-cost-display').text('Menghitung...');

            $.ajax({
                url: "{{ route('rajaongkir.shipping') }}",
                data: { district_id: distId, courier: courier },
                success: function(response) {
                    let costs = response.costs || [];
                    $('#courier_service').empty().append('<option value="">Pilih Layanan...</option>');
                    
                    if(costs.length > 0) {
                        costs.forEach(c => {
                            let label = `${c.service} - Rp ${parseInt(c.cost).toLocaleString('id-ID')} (${c.etd} Hari)`;
                            $('#courier_service').append(`<option value="${c.service}" data-cost="${c.cost}">${label}</option>`);
                        });
                        
                        // Otomatis pilih yang pertama & update harga
                        $('#courier_service option:eq(1)').prop('selected', true).trigger('change');
                    } else {
                        $('#courier_service').append('<option>Tidak ada layanan</option>');
                        $('#shipping-cost-display').text('Tidak tersedia');
                    }
                },
                error: function() {
                    $('#shipping-cost-display').text('Error API');
                }
            });
        }

        // Trigger Hitung Ongkir
        $('#district_id, #courier_code').change(function() {
            if ($('input[name="delivery_type"]:checked').val() === 'delivery') {
                calculateShipping();
            }
        });

        // Update Total Harga saat Layanan Dipilih
        $('#courier_service').change(function() {
            let cost = $(this).find(':selected').data('cost') || 0;
            $('#shipping-cost-display').text('Rp ' + parseInt(cost).toLocaleString('id-ID'));
            $('#total-payment').text('Rp ' + (subtotal + parseInt(cost)).toLocaleString('id-ID'));
        });

        // Toggle Tampilan Ekspedisi vs Pickup
        $('input[name="delivery_type"]').change(function() {
            if($(this).val() === 'delivery') {
                $('#courier-options').slideDown();
                // Jika data sudah lengkap, hitung ulang
                if($('#district_id').val()) calculateShipping();
            } else {
                $('#courier-options').slideUp();
                $('#shipping-cost-display').text('Rp 0');
                $('#total-payment').text('Rp ' + subtotal.toLocaleString('id-ID'));
            }
        });
    });
</script>
@endsection