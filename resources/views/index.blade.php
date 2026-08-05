<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi AI — MarsTop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0F172A;
            background: #F8FAFC;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        svg { display: block; }

        :root {
            --brand: #F97316;
            --brand-dark: #EA580C;
            --brand-light: #FFF7ED;
            --slate-900: #0F172A;
            --slate-800: #1E293B;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748B;
            --slate-400: #94A3B8;
            --slate-300: #CBD5E1;
            --slate-200: #E2E8F0;
            --slate-100: #F1F5F9;
            --slate-50: #F8FAFC;
            --success: #15803D;
            --success-bg: #F0FDF4;
            --success-border: #BBF7D0;
            --error: #DC2626;
            --error-bg: #FEF2F2;
            --error-border: #FECACA;
        }

        /* ── LAYOUT ── */
        .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
        .narrow { max-width: 620px; }

        /* ── NAV ── */
        .site-nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,0.96); backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--slate-200);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 60px; }
        .nav-logo { font-size: 17px; font-weight: 700; letter-spacing: -0.3px; color: var(--slate-900); }
        .nav-logo .accent { color: var(--brand); }
        .nav-link {
            font-size: 13px; font-weight: 500; color: var(--slate-600);
            padding: 6px 12px; border-radius: 6px;
            transition: color .15s, background .15s;
        }
        .nav-link:hover { color: var(--slate-900); background: var(--slate-100); }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-divider { width: 1px; height: 20px; background: var(--slate-200); margin: 0 4px; }

        /* ── PAGE WRAPPER ── */
        .page-main { padding: 48px 0 80px; }

        /* ── PAGE HEADER ── */
        .page-head { margin-bottom: 32px; }
        .page-head h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.4px; color: var(--slate-900); margin-bottom: 6px; }
        .page-head p { font-size: 14.5px; color: var(--slate-500); }

        /* ── STEPS INDICATOR ── */
        .step-indicator {
            display: flex; align-items: center; gap: 0;
            margin-bottom: 32px;
        }
        .si-step {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 500;
        }
        .si-num {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; flex-shrink: 0;
        }
        .si-step.done .si-num { background: var(--success); color: #fff; }
        .si-step.active .si-num { background: var(--brand); color: #fff; }
        .si-step.pending .si-num { background: var(--slate-200); color: var(--slate-500); }
        .si-step.done .si-label { color: var(--slate-600); }
        .si-step.active .si-label { color: var(--slate-900); font-weight: 600; }
        .si-step.pending .si-label { color: var(--slate-400); }
        .si-connector {
            flex: 0 0 32px; height: 1px;
            background: var(--slate-200); margin: 0 8px;
        }
        .si-connector.done { background: var(--success); }

        /* ── CARDS ── */
        .card {
            background: #fff; border: 1px solid var(--slate-200);
            border-radius: 12px; padding: 32px;
        }

        /* ── FORM ── */
        .form-group { margin-bottom: 22px; }
        .form-label {
            display: block; font-size: 14px; font-weight: 500;
            color: var(--slate-700); margin-bottom: 7px;
        }
        .form-control {
            width: 100%; border: 1px solid var(--slate-200);
            border-radius: 7px; padding: 10px 14px;
            font-size: 14px; font-family: inherit;
            color: var(--slate-900); background: #fff;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control::placeholder { color: var(--slate-400); }
        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        }
        textarea.form-control { resize: vertical; line-height: 1.6; }
        .form-hint { font-size: 12.5px; color: var(--slate-400); margin-top: 6px; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 14px; font-weight: 600; font-family: inherit;
            padding: 11px 20px; border-radius: 7px;
            cursor: pointer; border: none; transition: all .15s;
        }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: var(--brand-dark); }
        .btn-secondary {
            background: #fff; color: var(--slate-700);
            border: 1px solid var(--slate-200);
        }
        .btn-secondary:hover { border-color: var(--slate-300); background: var(--slate-50); }
        .btn-full { width: 100%; justify-content: center; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #166534; }

        /* ── ERROR BANNER ── */
        .alert-error {
            padding: 14px 16px; border-radius: 8px;
            background: var(--error-bg); border: 1px solid var(--error-border);
            color: var(--error); font-size: 13.5px; font-weight: 500;
            margin-bottom: 24px; display: flex; gap: 10px; align-items: flex-start;
        }
        .alert-error svg { flex-shrink: 0; margin-top: 2px; }

        /* ── AI RESULT CARD (the "receipt" signature element) ── */
        .result-card {
            background: #fff; border: 1px solid var(--slate-200);
            border-radius: 12px; overflow: hidden;
            margin-bottom: 24px;
        }
        .result-header {
            padding: 20px 24px; border-bottom: 1px solid var(--slate-100);
            display: flex; align-items: center; justify-content: space-between;
        }
        .result-header-left { display: flex; align-items: center; gap: 10px; }
        .result-header h2 { font-size: 15px; font-weight: 600; color: var(--slate-900); }
        .result-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600;
            background: #F0FDF4; color: var(--success);
            border: 1px solid var(--success-border);
            border-radius: 20px; padding: 3px 10px;
        }
        .result-body { padding: 0; }
        .result-row {
            display: flex; align-items: flex-start;
            padding: 14px 24px;
            border-bottom: 1px solid var(--slate-100);
        }
        .result-row:last-child { border-bottom: none; }
        .result-label {
            flex: 0 0 160px; font-size: 13px;
            font-weight: 500; color: var(--slate-400);
        }
        .result-value { font-size: 14px; color: var(--slate-800); font-weight: 500; }
        .result-value.highlight {
            display: inline-flex; align-items: center;
            background: #FFFBEB; color: #92400E;
            border: 1px solid #FDE68A;
            border-radius: 5px; padding: 2px 10px;
            font-size: 13px;
        }
        .result-value.cost { color: var(--success); font-size: 17px; font-weight: 700; }
        .result-note-row {
            padding: 12px 24px;
            background: var(--slate-50); border-top: 1px solid var(--slate-100);
        }
        .result-note { font-size: 12.5px; color: var(--slate-400); }
        .result-footer {
            padding: 16px 24px; display: flex;
            align-items: center; justify-content: flex-end; gap: 10px;
            border-top: 1px solid var(--slate-200);
            background: var(--slate-50);
        }

        /* ── BOOKING SUCCESS STATE ── */
        .success-state {
            background: #fff; border: 1px solid var(--success-border);
            border-radius: 12px; padding: 40px 32px; text-align: center;
        }
        .success-icon {
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--success-bg); display: flex;
            align-items: center; justify-content: center;
            margin: 0 auto 20px; color: var(--success);
        }
        .success-state h2 {
            font-size: 22px; font-weight: 700; color: var(--slate-900);
            margin-bottom: 8px; letter-spacing: -0.3px;
        }
        .success-state .lead { font-size: 14.5px; color: var(--slate-500); margin-bottom: 28px; }
        .booking-code-wrap {
            display: inline-block; margin: 0 auto 28px;
            padding: 16px 32px; border: 1.5px dashed var(--slate-300);
            border-radius: 10px; background: var(--slate-50);
        }
        .booking-code-label { font-size: 11px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--slate-400); margin-bottom: 6px; }
        .booking-code {
            font-family: 'Courier New', monospace;
            font-size: 28px; font-weight: 700;
            letter-spacing: 3px; color: var(--slate-900);
        }
        .success-hint {
            font-size: 13px; color: var(--slate-400);
            margin-bottom: 28px; padding: 0 24px;
        }
        .success-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }

        /* ── FOOTER ── */
        .site-footer { background: var(--slate-800); padding: 24px 0; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .footer-brand { font-size: 14px; font-weight: 700; color: #fff; }
        .footer-brand .accent { color: var(--brand); }
        .footer-copy { font-size: 12.5px; color: var(--slate-400); }

        /* ── FOCUS ── */
        a:focus-visible, button:focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }

        @media (max-width: 640px) {
            .card { padding: 24px 20px; }
            .result-row { flex-direction: column; gap: 4px; }
            .result-label { flex: none; }
            .result-footer { flex-direction: column-reverse; }
            .btn-full-mob { width: 100%; justify-content: center; }
            .step-indicator { display: none; }
            .success-actions { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav class="site-nav" aria-label="Navigasi utama">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="nav-logo">Mars<span class="accent">Top</span></a>
            <div class="nav-links">
                <a href="/cek-status" class="nav-link">Lacak Servis</a>
                <div class="nav-divider" aria-hidden="true"></div>
                <a href="/admin" class="nav-link">Portal Teknisi</a>
            </div>
        </div>
    </div>
</nav>

<main class="page-main">
    <div class="container narrow">

        <!-- PAGE HEADER -->
        <div class="page-head">
            <h1>Konsultasi AI</h1>
            <p>Ceritakan keluhan laptop Anda, dan AI akan menganalisa kerusakan serta estimasi biayanya.</p>
        </div>

        @if(session('booking_success_code'))
            {{-- ───── STATE 3: BOOKING SUCCESS ───── --}}

            <!-- Steps: done, done, active -->
            <div class="step-indicator" role="list" aria-label="Progres booking">
                <div class="si-step done" role="listitem">
                    <div class="si-num" aria-hidden="true">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6l3 3 5-5"/></svg>
                    </div>
                    <span class="si-label">Deskripsi</span>
                </div>
                <div class="si-connector done" aria-hidden="true"></div>
                <div class="si-step done" role="listitem">
                    <div class="si-num" aria-hidden="true">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6l3 3 5-5"/></svg>
                    </div>
                    <span class="si-label">Hasil Analisa</span>
                </div>
                <div class="si-connector done" aria-hidden="true"></div>
                <div class="si-step active" role="listitem">
                    <div class="si-num" aria-hidden="true">3</div>
                    <span class="si-label">Booking Terdaftar</span>
                </div>
            </div>

            <div class="success-state" role="region" aria-labelledby="success-heading">
                <div class="success-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="14" cy="14" r="12"/>
                        <path d="M8.5 14l4 4 7-7"/>
                    </svg>
                </div>
                <h2 id="success-heading">Booking Berhasil Terdaftar</h2>
                <p class="lead">Kode booking Anda sudah masuk dalam antrian servis MarsTop. Tunjukkan kode ini saat menyerahkan perangkat ke toko kami.</p>

                <div class="booking-code-wrap">
                    <div class="booking-code-label">Kode Booking Anda</div>
                    <div class="booking-code">{{ session('booking_success_code') }}</div>
                </div>

                <p class="success-hint">Screenshot atau catat kode ini. Konfirmasi booking juga telah dikirimkan ke email Anda.</p>

                <div class="success-actions">
                    <a href="/cek-status" class="btn btn-primary">Lacak Status Servis</a>
                    <a href="/konsultasi" class="btn btn-secondary">Konsultasi Baru</a>
                </div>
            </div>

        @elseif(session('success'))
            {{-- ───── STATE 2: AI RESULT ───── --}}

            <!-- Steps: done, active, pending -->
            <div class="step-indicator" role="list" aria-label="Progres booking">
                <div class="si-step done" role="listitem">
                    <div class="si-num" aria-hidden="true">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6l3 3 5-5"/></svg>
                    </div>
                    <span class="si-label">Deskripsi</span>
                </div>
                <div class="si-connector done" aria-hidden="true"></div>
                <div class="si-step active" role="listitem">
                    <div class="si-num" aria-hidden="true">2</div>
                    <span class="si-label">Hasil Analisa</span>
                </div>
                <div class="si-connector" aria-hidden="true"></div>
                <div class="si-step pending" role="listitem">
                    <div class="si-num" aria-hidden="true">3</div>
                    <span class="si-label">Konfirmasi Booking</span>
                </div>
            </div>

            <div class="result-card" role="region" aria-labelledby="result-heading">
                <div class="result-header">
                    <div class="result-header-left">
                        <h2 id="result-heading">Hasil Analisa AI</h2>
                    </div>
                    <span class="result-badge">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                        Analisa Selesai
                    </span>
                </div>
                <div class="result-body">
                    <div class="result-row">
                        <span class="result-label">Perangkat</span>
                        <span class="result-value">{{ session('ticket')->perangkat }}</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Kerusakan Terdeteksi</span>
                        <span class="result-value">{{ session('ticket')->kendala }}</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Prediksi Sparepart</span>
                        <span class="result-value highlight">{{ session('ticket')->estimasi_sparepart }}</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Estimasi Biaya</span>
                        <span class="result-value cost">Rp {{ number_format(session('ticket')->estimasi_biaya, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="result-note-row">
                    <p class="result-note">Ini adalah estimasi awal dari AI. Biaya final dapat berbeda setelah pemeriksaan langsung oleh teknisi di toko.</p>
                </div>
                <div class="result-footer">
                    <a href="/konsultasi" class="btn btn-secondary">Ubah Keluhan</a>
                    <form action="/konfirmasi-booking/{{ session('ticket_id') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Konfirmasi &amp; Daftar Antrean
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        @else
            {{-- ───── STATE 1: DEFAULT FORM ───── --}}

            <!-- Steps: all pending -->
            <div class="step-indicator" role="list" aria-label="Progres booking">
                <div class="si-step active" role="listitem">
                    <div class="si-num" aria-hidden="true">1</div>
                    <span class="si-label">Deskripsi Keluhan</span>
                </div>
                <div class="si-connector" aria-hidden="true"></div>
                <div class="si-step pending" role="listitem">
                    <div class="si-num" aria-hidden="true">2</div>
                    <span class="si-label">Hasil Analisa</span>
                </div>
                <div class="si-connector" aria-hidden="true"></div>
                <div class="si-step pending" role="listitem">
                    <div class="si-num" aria-hidden="true">3</div>
                    <span class="si-label">Konfirmasi Booking</span>
                </div>
            </div>

            @if(session('error'))
                <div class="alert-error" role="alert">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="8" cy="8" r="7"/><path d="M8 5v3M8 10.5v.5"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <form action="/kirim-aduan" method="POST" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input
                            type="email" name="email" id="email"
                            class="form-control"
                            placeholder="nama@email.com"
                            required
                            autocomplete="email"
                        >
                        <p class="form-hint">Kode booking akan dikirimkan ke email ini setelah konfirmasi.</p>
                    </div>
                    <div class="form-group">
                        <label for="aduan" class="form-label">Ceritakan Keluhan Laptop Anda</label>
                        <textarea
                            class="form-control" id="aduan" name="aduan"
                            rows="5"
                            placeholder="Contoh: Laptop Asus saya mendadak mati total saat dicolok charger. Layarnya tiba-tiba hitam dan tidak bisa dinyalakan lagi..."
                            required
                        ></textarea>
                        <p class="form-hint">Gunakan bahasa sehari-hari. Semakin detail, semakin akurat analisa AI.</p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M11.5 2.5L4 9h5l-1 4.5 6-7H9z"/>
                        </svg>
                        Analisa dengan AI
                    </button>
                </form>
            </div>

        @endif

    </div>
</main>

<footer class="site-footer" aria-label="Footer">
    <div class="container">
        <div class="footer-inner">
            <span class="footer-brand">Mars<span class="accent">Top</span></span>
            <span class="footer-copy">&copy; 2026 MarsTop Service Center</span>
        </div>
    </div>
</footer>

</body>
</html>
