@extends('layouts.app')

@section('title', 'MarsTop — Servis Laptop Cerdas Berbasis AI')

@push('styles')
<style>
    html { scroll-behavior: smooth; }
    body { background: #FFFFFF; }

    .nav-link {
        font-weight: 600;
        padding: 7px 14px;
        border: 1px solid var(--slate-200);
        background: var(--slate-50);
    }
    .nav-link:hover { border-color: var(--slate-300); background: var(--slate-100); color: var(--slate-700); }
    .nav-link.active { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }

    /* ── HERO ── */
    .hero { background: var(--slate-900); padding: 96px 0 104px; }
    .hero-tag {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: 11.5px; font-weight: 600;
        letter-spacing: 0.07em; text-transform: uppercase;
        color: var(--brand); background: rgba(249,115,22,0.1);
        border: 1px solid rgba(249,115,22,0.22);
        border-radius: 20px; padding: 4px 12px; margin-bottom: 28px;
    }
    .hero-tag svg { flex-shrink: 0; }
    .hero h1 {
        font-size: clamp(36px, 5.5vw, 60px);
        font-weight: 700; line-height: 1.13;
        letter-spacing: -1.5px; color: #FFFFFF;
        margin-bottom: 22px;
    }
    .hero h1 .accent { color: var(--brand); }
    .hero-lead { font-size: 17px; line-height: 1.7; color: var(--slate-400); max-width: 500px; margin-bottom: 40px; }
    .hero-ctas { display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-brand {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--brand); color: #fff;
        font-size: 14px; font-weight: 600;
        padding: 12px 22px; border-radius: 8px;
        border: none; cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-brand:hover { background: var(--brand-dark); }
    .btn-brand:active { transform: scale(0.98); }
    .btn-ghost {
        display: inline-flex; align-items: center; gap: 8px;
        background: transparent; color: var(--slate-300);
        font-size: 14px; font-weight: 600;
        padding: 12px 22px; border-radius: 8px;
        border: 1px solid var(--slate-700);
        cursor: pointer; transition: border-color .15s, color .15s;
    }
    .btn-ghost:hover { border-color: var(--slate-500); color: #fff; }

    /* ── FEATURES ── */
    .features { padding: 88px 0; background: #fff; }
    .section-head { text-align: center; margin-bottom: 52px; }
    .section-head h2 { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; color: var(--slate-900); margin-bottom: 10px; }
    .section-head p { font-size: 15px; color: var(--slate-500); max-width: 440px; margin: 0 auto; }
    .features-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
    .feature-card {
        padding: 28px 24px; border: 1px solid var(--slate-200);
        border-radius: 12px; background: #fff;
        transition: border-color .2s;
    }
    .feature-card:hover { border-color: var(--slate-300); }
    .fi {
        width: 40px; height: 40px;
        background: var(--brand-light); border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--brand); margin-bottom: 18px; flex-shrink: 0;
    }
    .feature-card h3 { font-size: 15px; font-weight: 600; color: var(--slate-900); margin-bottom: 8px; }
    .feature-card p { font-size: 14px; color: var(--slate-500); line-height: 1.65; }

    /* ── STEPS ── */
    .steps { padding: 88px 0; background: var(--slate-50); border-top: 1px solid var(--slate-200); border-bottom: 1px solid var(--slate-200); }
    .steps-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 40px; }
    .step { text-align: center; }
    .step-num {
        display: inline-flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--slate-900); color: #fff;
        font-size: 14px; font-weight: 700; margin-bottom: 16px;
    }
    .step h3 { font-size: 14px; font-weight: 600; color: var(--slate-900); margin-bottom: 8px; }
    .step p { font-size: 14px; color: var(--slate-500); line-height: 1.65; }

    @media (max-width: 768px) {
        .hero { padding: 64px 0 72px; }
        .features-grid { grid-template-columns: 1fr; }
        .steps-grid { grid-template-columns: 1fr; gap: 28px; }
    }
    @media (max-width: 500px) {
        .hero-ctas { flex-direction: column; }
        .btn-brand, .btn-ghost { justify-content: center; }
    }
</style>
@endpush

@section('content')

<header class="hero">
    <div class="container">
        <p class="hero-tag">
            <svg width="6" height="6" viewBox="0 0 6 6" aria-hidden="true"><circle cx="3" cy="3" r="3" fill="currentColor"/></svg>
            Platform Servis Laptop Berbasis AI
        </p>
        <h1>Servis laptop,<br>dimulai dari <span class="accent">rumah.</span></h1>
        <p class="hero-lead">Konsultasikan masalah laptop Anda dengan AI, dapatkan estimasi biaya yang transparan, dan pantau proses servis secara real-time.</p>
        <div class="hero-ctas">
            <a href="/konsultasi" class="btn-brand">
                Mulai Konsultasi AI
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
            </a>
            <a href="/cek-status" class="btn-ghost">Lacak Status Servis</a>
        </div>
    </div>
</header>

<section class="features" aria-labelledby="features-heading">
    <div class="container">
        <div class="section-head">
            <h2 id="features-heading">Mengapa MarsTop?</h2>
            <p>Layanan modern yang mengutamakan transparansi dan kenyamanan Anda.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="fi" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11.5 2.5L4 11h6l-1.5 6.5 7.5-9h-5.5z"/>
                    </svg>
                </div>
                <h3>Diagnosis AI Instan</h3>
                <p>Cukup ceritakan keluhan laptop dengan bahasa sehari-hari. AI kami langsung menganalisa komponen yang bermasalah dan estimasi biayanya.</p>
            </div>
            <div class="feature-card">
                <div class="fi" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v1H2V6zM2 10h16v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6z"/>
                        <path d="M6 14h2M10 14h4"/>
                    </svg>
                </div>
                <h3>Biaya Transparan</h3>
                <p>Estimasi harga diberikan secara terbuka sebelum Anda datang ke toko. Tidak ada biaya tersembunyi, tidak ada tebak-tebakan.</p>
            </div>
            <div class="feature-card">
                <div class="fi" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="10" cy="10" r="7"/>
                        <path d="M10 6.5v4l2.5 2.5"/>
                    </svg>
                </div>
                <h3>Real-Time Tracking</h3>
                <p>Pantau status perbaikan laptop dari mana saja menggunakan kode booking unik milik Anda tanpa perlu telepon ke toko.</p>
            </div>
        </div>
    </div>
</section>

<section class="steps" aria-labelledby="steps-heading">
    <div class="container">
        <div class="section-head">
            <h2 id="steps-heading">3 Langkah Mudah Servis Laptop</h2>
        </div>
        <div class="steps-grid">
            <div class="step">
                <div class="step-num" aria-hidden="true">1</div>
                <h3>Konsultasi dari Rumah</h3>
                <p>Isi keluhan laptop Anda secara online dan lihat prediksi biaya perbaikannya sebelum ke toko.</p>
            </div>
            <div class="step">
                <div class="step-num" aria-hidden="true">2</div>
                <h3>Booking &amp; Serah Barang</h3>
                <p>Konfirmasi booking antrean, lalu bawa perangkat beserta kode unik Anda ke toko fisik kami.</p>
            </div>
            <div class="step">
                <div class="step-num" aria-hidden="true">3</div>
                <h3>Pantau Progres Mandiri</h3>
                <p>Duduk santai di rumah sambil melacak status pengerjaan perangkat Anda hingga selesai.</p>
            </div>
        </div>
    </div>
</section>

@endsection

@section('footer-copy', 'Ruko MarsTop Square, Tangerang · 09:00–21:00 WIB · © 2026')
