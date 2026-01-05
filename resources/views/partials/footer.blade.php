<footer class="mt-auto pt-5 pb-4" style="background-color: var(--color-black); color: var(--color-white);">
    <div class="container">
        <div class="row gy-5">
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="fw-normal text-white mb-4 text-uppercase font-serif">Adella Kebaya</h3>
                <p class="small fw-light text-white opacity-75 mb-4">Menyewakan dan menjual kebaya berkualitas tinggi untuk momen spesial Anda.</p>
                <div class="d-flex gap-4">
                    <a href="#" class="text-white opacity-75 hover-opacity-100"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white opacity-75 hover-opacity-100"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-white opacity-75 hover-opacity-100"><i class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-2">
                <h5 class="text-white mb-4 text-uppercase small" style="letter-spacing: 0.15em;">Menu</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 small opacity-75">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-white">Beranda</a></li>
                    <li><a href="{{ url('/katalog') }}" class="text-decoration-none text-white">Katalog</a></li>
                    <li><a href="{{ url('/sewa') }}" class="text-decoration-none text-white">Sewa</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-6 col-lg-3">
                <h5 class="text-white mb-4 text-uppercase small" style="letter-spacing: 0.15em;">Lokasi</h5>
                <ul class="list-unstyled small opacity-75 d-flex flex-column gap-3">
                    <li><strong class="d-block text-white text-uppercase">Surabaya</strong> Jl. Mayjend Sungkono No 98</li>
                    <li><strong class="d-block text-white text-uppercase">Bojonegoro</strong> Jl. Jenderal Sudirman No 55</li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="text-white mb-4 text-uppercase small" style="letter-spacing: 0.15em;">Kontak</h5>
                <ul class="list-unstyled small opacity-75 d-flex flex-column gap-3">
                    <li class="d-flex"><i class="bi bi-telephone me-3"></i> +62 812-3456-7890</li>
                    <li class="d-flex"><i class="bi bi-envelope me-3"></i> info@adellakebaya.com</li>
                    <li class="d-flex"><i class="bi bi-clock me-3"></i> Senin - Sabtu (09:00 - 17:00)</li>
                </ul>
            </div>
        </div>

        <div class="border-top border-secondary mt-5 pt-4 text-center small opacity-50">
            <p class="mb-0 text-uppercase" style="letter-spacing: 0.1em;">&copy; {{ date('Y') }} Adella Kebaya. All rights reserved.</p>
        </div>
    </div>
</footer>