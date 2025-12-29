<footer class="mt-auto pt-5 pb-4 border-top" style="background-color: var(--color-black); color: var(--color-white); border-color: var(--color-black) !important;">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-4 col-md-6 pe-lg-5">
                <h3 class="fw-normal text-white mb-4 text-uppercase" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Adella Kebaya</h3>
                <p class="small fw-light text-white opacity-75 mb-4" style="line-height: 1.8;">Menyewakan dan menjual kebaya berkualitas tinggi untuk momen spesial Anda. Tampil anggun dengan warisan budaya Indonesia dalam balutan modernitas.</p>
                <div class="d-flex gap-4">
                    <a href="#" class="text-white hover-opacity-75 transition-all"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white hover-opacity-75 transition-all"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-white hover-opacity-75 transition-all"><i class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.15em;">Menu</h5>
                <ul class="list-unstyled d-flex flex-column gap-3 small">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-white opacity-75 hover-opacity-100 transition-all text-uppercase" style="letter-spacing: 0.05em;">Beranda</a></li>
                    <li><a href="{{ url('/katalog') }}" class="text-decoration-none text-white opacity-75 hover-opacity-100 transition-all text-uppercase" style="letter-spacing: 0.05em;">Koleksi</a></li>
                    <li><a href="{{ url('/about') }}" class="text-decoration-none text-white opacity-75 hover-opacity-100 transition-all text-uppercase" style="letter-spacing: 0.05em;">Tentang Kami</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-4 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.15em;">Lokasi Butik</h5>
                <ul class="list-unstyled small opacity-75 d-flex flex-column gap-3">
                    <li class="d-flex">
                        <i class="bi bi-geo-alt me-3 text-white"></i>
                        <div>
                            <strong class="d-block text-white text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Surabaya</strong>
                            <span class="fw-light">Jl. Mayjend Sungkono No 98</span>
                        </div>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-geo-alt me-3 text-white"></i>
                        <div>
                            <strong class="d-block text-white text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Bojonegoro</strong>
                            <span class="fw-light">Jl. Jenderal Sudirman No 55</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-4 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.15em;">Hubungi Kami</h5>
                <ul class="list-unstyled small opacity-75 d-flex flex-column gap-3">
                    <li class="d-flex align-items-center">
                        <i class="bi bi-telephone me-3 text-white"></i> 
                        <span class="fw-light">+62 812-3456-7890</span>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="bi bi-envelope me-3 text-white"></i> 
                        <span class="fw-light">info@adellakebaya.com</span>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="bi bi-clock me-3 text-white"></i> 
                        <span class="fw-light">Senin - Sabtu (09:00 - 17:00)</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-top border-secondary mt-5 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center small opacity-50">
            <p class="mb-0 text-uppercase" style="letter-spacing: 0.1em; font-size: 0.7rem;">&copy; {{ date('Y') }} Adella Kebaya.</p>
            <p class="mb-0 text-uppercase" style="letter-spacing: 0.1em; font-size: 0.7rem;">All rights reserved.</p>
        </div>
    </div>
</footer>