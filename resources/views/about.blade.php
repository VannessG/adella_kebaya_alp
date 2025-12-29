@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="container pb-4 text-center">
    <h1 class="display-4 fw-normal text-uppercase text-black mb-2" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Tentang Adella Kebaya</h1>
    <div style="width: 60px; height: 1px; background-color: #000; margin: 20px auto;"></div>
    <p class="lead text-muted fw-light" style="max-width: 700px; margin: 0 auto; letter-spacing: 0.05em;">
        Destinasi terpercaya bagi wanita Indonesia yang mendambakan keanggunan dalam setiap jahitan kebaya.
    </p>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border rounded-0 shadow-sm bg-white p-4 p-lg-5" style="border-color: var(--border-color);">
                <div class="row g-5">
                    <div class="col-lg-7 pe-lg-5">
                        <section class="mb-5">
                            <h4 class="fw-normal text-black text-uppercase mb-3" style="font-family: 'Marcellus', serif; letter-spacing: 0.05em;">Sejarah Kami</h4>
                            <p class="text-muted small" style="line-height: 1.8; text-align: justify;">
                                Berdiri sejak tahun 2020, Adella Kebaya lahir dari kecintaan mendalam terhadap warisan budaya Indonesia. Bermula dari melayani kerabat dekat, apresiasi tinggi terhadap detail dan kualitas membawa kami memperluas jangkauan untuk melayani wanita Indonesia yang ingin tampil istimewa di hari bahagia mereka.
                            </p>
                        </section>

                        <section class="mb-5">
                            <h4 class="fw-normal text-black text-uppercase mb-3" style="font-family: 'Marcellus', serif; letter-spacing: 0.05em;">Koleksi & Layanan</h4>
                            <ul class="list-unstyled text-muted small ps-0" style="line-height: 2;">
                                <li class="mb-2 border-bottom pb-2" style="border-color: #F0F0F0 !important;">
                                    <strong class="text-black text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em;">Kebaya Modern</strong> 
                                    <br> Desain kontemporer dengan sentuhan material premium.
                                </li>
                                <li class="mb-2 border-bottom pb-2" style="border-color: #F0F0F0 !important;">
                                    <strong class="text-black text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em;">Kebaya Tradisional</strong> 
                                    <br> Potongan klasik yang menjaga keaslian pakem budaya.
                                </li>
                                <li class="mb-2 border-bottom pb-2" style="border-color: #F0F0F0 !important;">
                                    <strong class="text-black text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em;">Layanan Custom</strong> 
                                    <br> Personalisasi desain sesuai karakter dan kebutuhan Anda.
                                </li>
                            </ul>
                        </section>

                        <section>
                            <h4 class="fw-normal text-black text-uppercase mb-3" style="font-family: 'Marcellus', serif; letter-spacing: 0.05em;">Dedikasi Kualitas</h4>
                            <p class="text-muted small" style="line-height: 1.8; text-align: justify;">
                                Kami hanya menggunakan material terbaik—brokat premium, sutra alam, hingga detail payet yang dikerjakan dengan tangan. Setiap helai benang adalah wujud komitmen kami untuk menghadirkan karya seni yang tidak hanya indah dipandang, tetapi juga nyaman dikenakan.
                            </p>
                        </section>
                    </div>

                    <div class="col-lg-5 ps-lg-5 border-start" style="border-color: var(--border-color) !important;">
                        <div class="row g-4 mb-5">
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3 opacity-75"><i class="bi bi-gem fs-2 text-black"></i></div>
                                    <div>
                                        <h6 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.1em; font-size: 0.75rem;">Premium Quality</h6>
                                        <p class="small text-muted mb-0">Material & jahitan berstandar butik.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3 opacity-75"><i class="bi bi-stars fs-2 text-black"></i></div>
                                    <div>
                                        <h6 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.1em; font-size: 0.75rem;">Eksklusivitas</h6>
                                        <p class="small text-muted mb-0">Desain unik yang tidak pasaran.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 opacity-75"><i class="bi bi-emoji-smile fs-2 text-black"></i></div>
                                    <div>
                                        <h6 class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.1em; font-size: 0.75rem;">Kepuasan Pelanggan</h6>
                                        <p class="small text-muted mb-0">Ribuan pelanggan bahagia sejak 2020.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-subtle border rounded-0 text-center" style="border-color: var(--border-color);">
                            <h6 class="fw-normal text-uppercase mb-4 text-black" style="font-family: 'Marcellus', serif; letter-spacing: 0.1em;">Hubungi Butik</h6>
                            <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-primary-custom w-100 rounded-0 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.1em;"><i class="bi bi-whatsapp me-2"></i> Chat WhatsApp</a>
                            <div class="small text-muted mt-3">
                                <p class="mb-1"><i class="bi bi-envelope me-2"></i> info@adellakebaya.com</p>
                                <p class="mb-0"><i class="bi bi-clock me-2"></i> Senin - Sabtu (09:00 - 17:00)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection