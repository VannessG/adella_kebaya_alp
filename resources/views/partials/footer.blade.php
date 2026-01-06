<footer class="mt-auto pt-5 pb-4" style="background-color: var(--color-black); color: var(--color-white);">
    <div class="container">
        <div class="row gy-5">
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="fw-normal text-white mb-4 text-uppercase font-serif">Adella Kebaya</h3>
                <p class="small fw-light text-white opacity-75 mb-4">Menyewakan dan menjual kebaya berkualitas tinggi untuk momen spesial Anda.</p>
                <div class="d-flex gap-4">
                    <a href="https://www.instagram.com/adella_kebaya?igsh=MWFodjBmdzd5YzA1dg==" target="_blank" class="text-white opacity-75 hover-opacity-100">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="https://www.facebook.com/share/1NoQFGRB75/?mibextid=wwXIfr" target="_blank" class="text-white opacity-75 hover-opacity-100">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="https://wa.me/6289678956340" target="_blank" class="text-white opacity-75 hover-opacity-100">
                        <i class="bi bi-whatsapp fs-5"></i>
                    </a>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-2">
                <h5 class="text-white mb-4 text-uppercase small" style="letter-spacing: 0.15em;">Menu</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 small opacity-75">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-white">Beranda</a></li>
                    <li><a href="{{ url('/katalog') }}" class="text-decoration-none text-white">Katalog</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-6 col-lg-3">
                <h5 class="text-white mb-4 text-uppercase small" style="letter-spacing: 0.15em;">Lokasi</h5>
                <ul class="list-unstyled small opacity-75 d-flex flex-column gap-3">
                    <li>
                        <strong class="d-block text-white text-uppercase mb-1">Sidoarjo (Pondok Tjandra)</strong> 
                        Jl. Manggis VIII No.664, Tambaksari, Wadungasri, Kec. Waru, Kab. Sidoarjo, Jawa Timur 61256
                    </li>
                    <li>
                        <strong class="d-block text-white text-uppercase mb-1">Bojonegoro</strong> 
                        Ruko Central Point, Jl. Veteran No.19, Jambean, Sukorejo, Kec. Bojonegoro, Kab. Bojonegoro, Jawa Timur 62115
                    </li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="text-white mb-4 text-uppercase small" style="letter-spacing: 0.15em;">Kontak & Jam Buka</h5>
                <ul class="list-unstyled small opacity-75 d-flex flex-column gap-3">
                    <li class="d-flex">
                        <i class="bi bi-telephone me-3 fs-6"></i> 
                        <span>0896-7895-6340</span>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-clock me-3 fs-6"></i>
                        <div>
                            <strong class="d-block text-white">Sidoarjo:</strong>
                            Senin - Sabtu (09:00 - 17:00)
                        </div>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-clock me-3 fs-6"></i>
                        <div>
                            <strong class="d-block text-white">Bojonegoro:</strong>
                            Selasa - Minggu (10:00 - 18:00)
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-top border-secondary mt-5 pt-4 text-center small opacity-50">
            <p class="mb-0 text-uppercase" style="letter-spacing: 0.1em;">&copy; {{ date('Y') }} Adella Kebaya. All rights reserved.</p>
        </div>
    </div>
</footer>