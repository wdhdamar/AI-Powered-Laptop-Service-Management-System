<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Servis — MarsTop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0F172A; background: #F8FAFC;
            line-height: 1.6; -webkit-font-smoothing: antialiased;
            min-height: 100vh; display: flex; flex-direction: column;
        }
        a { color: inherit; text-decoration: none; }
        svg { display: block; }

        :root {
            --brand: #F97316; --brand-dark: #EA580C; --brand-light: #FFF7ED;
            --slate-900: #0F172A; --slate-800: #1E293B; --slate-700: #334155;
            --slate-600: #475569; --slate-500: #64748B; --slate-400: #94A3B8;
            --slate-300: #CBD5E1; --slate-200: #E2E8F0; --slate-100: #F1F5F9;
            --slate-50: #F8FAFC; --white: #FFFFFF;
            --success: #15803D; --success-bg: #F0FDF4; --success-border: #BBF7D0;
            --warning: #B45309; --warning-bg: #FFFBEB; --warning-border: #FDE68A;
            --info: #1D4ED8; --info-bg: #EFF6FF; --info-border: #BFDBFE;
            --error: #DC2626; --error-bg: #FEF2F2; --error-border: #FECACA;
        }

        .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
        .narrow { max-width: 600px; }

        /* NAV */
        .site-nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,0.96); backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--slate-200);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 60px; }
        .nav-logo { font-size: 17px; font-weight: 700; letter-spacing: -0.3px; color: var(--slate-900); }
        .nav-logo .accent { color: var(--brand); }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-link {
            font-size: 13px; font-weight: 500; color: var(--slate-600);
            padding: 6px 12px; border-radius: 6px;
            transition: color .15s, background .15s;
        }
        .nav-link:hover { color: var(--slate-900); background: var(--slate-100); }
        .nav-divider { width: 1px; height: 20px; background: var(--slate-200); margin: 0 4px; }

        /* PAGE */
        .page-main { padding: 48px 0 80px; flex: 1; }
        .page-head { margin-bottom: 32px; }
        .page-head h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.4px; color: var(--slate-900); margin-bottom: 6px; }
        .page-head p { font-size: 14.5px; color: var(--slate-500); }

        /* CARD */
        .card { background: #fff; border: 1px solid var(--slate-200); border-radius: 12px; }
        .card-body { padding: 28px; }

        /* FORM */
        .form-label { display: block; font-size: 14px; font-weight: 500; color: var(--slate-700); margin-bottom: 7px; }
        .input-group { display: flex; gap: 10px; }
        .form-control {
            flex: 1; border: 1px solid var(--slate-200); border-radius: 7px;
            padding: 10px 14px; font-size: 14px; font-family: inherit;
            color: var(--slate-900); background: #fff; outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control::placeholder { color: var(--slate-400); }
        .form-control:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(249,115,22,0.12); }
        .form-control.mono { font-family: 'Courier New', monospace; letter-spacing: 1.5px; font-size: 15px; }
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 14px; font-weight: 600; font-family: inherit;
            padding: 11px 20px; border-radius: 7px;
            cursor: pointer; border: none; transition: all .15s; white-space: nowrap;
        }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: var(--brand-dark); }
        .btn-secondary {
            background: #fff; color: var(--slate-700);
            border: 1px solid var(--slate-200);
        }
        .btn-secondary:hover { border-color: var(--slate-300); }
        .form-hint { font-size: 12.5px; color: var(--slate-400); margin-top: 6px; }

        /* ERROR */
        .alert-error {
            padding: 14px 16px; border-radius: 8px;
            background: var(--error-bg); border: 1px solid var(--error-border);
            color: var(--error); font-size: 13.5px; font-weight: 500;
            margin-top: 16px; display: flex; gap: 10px; align-items: flex-start;
        }
        .alert-error svg { flex-shrink: 0; margin-top: 1px; }

        /* STATUS RESULT */
        .result-card { background: #fff; border: 1px solid var(--slate-200); border-radius: 12px; margin-top: 24px; overflow: hidden; }
        .result-header { padding: 20px 24px; border-bottom: 1px solid var(--slate-100); }
        .result-header h2 { font-size: 15px; font-weight: 600; color: var(--slate-900); }

        /* STATUS STEPPER */
        .status-stepper {
            padding: 28px 24px; border-bottom: 1px solid var(--slate-100);
        }
        .stepper-track {
            display: flex; align-items: center; position: relative;
        }
        .stepper-step {
            display: flex; flex-direction: column; align-items: center; flex: 1;
            position: relative; z-index: 1;
        }
        .stepper-step:not(:last-child)::after {
            content: '';
            position: absolute; top: 19px; left: 50%;
            width: 100%; height: 2px;
            background: var(--slate-200); z-index: -1;
        }
        .stepper-step.done:not(:last-child)::after { background: var(--success); }
        .stepper-step.active:not(:last-child)::after { background: var(--slate-200); }

        .stepper-circle {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px; border: 2px solid var(--slate-200);
            background: #fff; flex-shrink: 0; transition: all .2s;
        }
        .stepper-step.done .stepper-circle { background: var(--success-bg); border-color: var(--success); color: var(--success); }
        .stepper-step.active .stepper-circle { background: var(--brand-light); border-color: var(--brand); color: var(--brand); }
        .stepper-step.pending .stepper-circle { background: #fff; border-color: var(--slate-200); color: var(--slate-300); }

        .stepper-label { font-size: 12px; font-weight: 600; text-align: center; line-height: 1.3; }
        .stepper-step.done .stepper-label { color: var(--success); }
        .stepper-step.active .stepper-label { color: var(--brand); }
        .stepper-step.pending .stepper-label { color: var(--slate-400); }

        .status-description {
            margin-top: 20px; padding: 14px 16px;
            border-radius: 8px; font-size: 13.5px; font-weight: 500; line-height: 1.55;
        }
        .status-description.antrean { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-border); }
        .status-description.diperbaiki { background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border); }
        .status-description.selesai { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }

        /* RESULT ROWS */
        .result-rows { padding: 8px 0; }
        .result-row {
            display: flex; align-items: flex-start;
            padding: 13px 24px; border-bottom: 1px solid var(--slate-50);
        }
        .result-row:last-child { border-bottom: none; }
        .result-label { flex: 0 0 150px; font-size: 13px; font-weight: 500; color: var(--slate-400); }
        .result-value { font-size: 14px; color: var(--slate-800); font-weight: 500; }
        .result-value.mono { font-family: 'Courier New', monospace; letter-spacing: 1px; color: var(--brand); font-size: 15px; }
        .result-value .badge {
            display: inline-flex; padding: 2px 10px;
            background: var(--warning-bg); color: var(--warning);
            border: 1px solid var(--warning-border);
            border-radius: 5px; font-size: 12.5px;
        }
        .cost-final { color: var(--success); font-size: 18px; font-weight: 700; }
        .cost-estimate { color: var(--slate-600); font-size: 15px; font-weight: 600; }
        .cost-note { font-size: 12px; color: var(--slate-400); font-weight: 400; margin-left: 6px; }

        /* FOOTER */
        .site-footer { background: var(--slate-800); padding: 24px 0; margin-top: auto; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .footer-brand { font-size: 14px; font-weight: 700; color: #fff; }
        .footer-brand .accent { color: var(--brand); }
        .footer-copy { font-size: 12.5px; color: var(--slate-400); }

        a:focus-visible, button:focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }

        @media (max-width: 640px) {
            .input-group { flex-direction: column; }
            .result-row { flex-direction: column; gap: 4px; }
            .result-label { flex: none; }
            .stepper-label { display: none; }
        }
    </style>
</head>
<body>

<nav class="site-nav" aria-label="Navigasi utama">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="nav-logo">Mars<span class="accent">Top</span></a>
            <div class="nav-links">
                <a href="/konsultasi" class="nav-link">Konsultasi AI</a>
                <div class="nav-divider" aria-hidden="true"></div>
                <a href="/admin" class="nav-link">Portal Teknisi</a>
            </div>
        </div>
    </div>
</nav>

<main class="page-main">
    <div class="container narrow">

        <div class="page-head">
            <h1>Lacak Status Servis</h1>
            <p>Masukkan kode booking unik Anda untuk melihat progres pengerjaan perangkat.</p>
        </div>

        <!-- SEARCH FORM -->
        <div class="card">
            <div class="card-body">
                <form action="/cek-status" method="POST">
                    @csrf
                    <label for="kode_booking" class="form-label">Kode Booking Anda</label>
                    <div class="input-group">
                        <input
                            type="text" name="kode_booking" id="kode_booking"
                            class="form-control mono"
                            placeholder="SRV-2026-0001"
                            value="{{ $kodeBooking ?? '' }}"
                            required
                            autocomplete="off"
                            aria-describedby="kode-hint"
                        >
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="6.5" cy="6.5" r="4.5"/><path d="M14 14l-3-3"/>
                            </svg>
                            Cari
                        </button>
                    </div>
                    <p id="kode-hint" class="form-hint">Format: SRV-TAHUN-NOMOR (contoh: SRV-2026-0001)</p>
                </form>

                @if(session('error_cari'))
                    <div class="alert-error" role="alert">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="8" cy="8" r="7"/><path d="M8 5v3M8 10.5v.5"/>
                        </svg>
                        {{ session('error_cari') }}
                    </div>
                @endif
            </div>
        </div>

        @if(isset($ticket))
            <div class="result-card" role="region" aria-labelledby="result-heading">
                <div class="result-header">
                    <h2 id="result-heading">Informasi Perangkat</h2>
                </div>

                <!-- STATUS STEPPER -->
                <div class="status-stepper">
                    <div class="stepper-track" role="list" aria-label="Status servis">
                        {{-- Step 1: Terdaftar --}}
                        <div class="stepper-step done" role="listitem">
                            <div class="stepper-circle" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 9l4 4 6-6"/>
                                </svg>
                            </div>
                            <span class="stepper-label">Terdaftar</span>
                        </div>

                        {{-- Step 2: Diperbaiki --}}
                        <div class="stepper-step {{ in_array($ticket->status, ['sedang_diperbaiki','selesai']) ? 'done' : ($ticket->status == 'antrean' ? 'active' : 'pending') }}" role="listitem">
                            <div class="stepper-circle" aria-hidden="true">
                                @if(in_array($ticket->status, ['sedang_diperbaiki','selesai']))
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 9l4 4 6-6"/>
                                    </svg>
                                @else
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M13.5 4.5a3 3 0 01-3.5 4.9L5 14.5 3.5 13l5.1-5c-.6-.9-.8-2.1-.3-3.1A3 3 0 0113.5 4.5z"/>
                                    </svg>
                                @endif
                            </div>
                            <span class="stepper-label">Diperbaiki</span>
                        </div>

                        {{-- Step 3: Selesai --}}
                        <div class="stepper-step {{ $ticket->status == 'selesai' ? 'active' : 'pending' }}" role="listitem">
                            <div class="stepper-circle" aria-hidden="true">
                                @if($ticket->status == 'selesai')
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 9l4 4 6-6"/>
                                    </svg>
                                @else
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 2v4M9 12v4M2 9h4M12 9h4M4.2 4.2l2.8 2.8M11 11l2.8 2.8M4.2 13.8l2.8-2.8M11 7l2.8-2.8"/>
                                    </svg>
                                @endif
                            </div>
                            <span class="stepper-label">Siap Diambil</span>
                        </div>
                    </div>

                    {{-- Status description --}}
                    @if($ticket->status == 'antrean')
                        <div class="status-description antrean" role="status">
                            Perangkat Anda sudah terdaftar. Silakan bawa laptop ke toko fisik kami beserta kode booking untuk diserahkan ke teknisi.
                        </div>
                    @elseif($ticket->status == 'sedang_diperbaiki')
                        <div class="status-description diperbaiki" role="status">
                            Perangkat sedang dalam proses perbaikan oleh teknisi kami. Anda akan menerima notifikasi email ketika servis selesai.
                        </div>
                    @elseif($ticket->status == 'selesai')
                        <div class="status-description selesai" role="status">
                            Perbaikan selesai! Perangkat Anda sudah dites dan siap diambil di toko kami.
                        </div>
                    @endif
                </div>

                <!-- RESULT ROWS -->
                <div class="result-rows">
                    <div class="result-row">
                        <span class="result-label">Kode Booking</span>
                        <span class="result-value mono">{{ $ticket->kode_booking }}</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Perangkat</span>
                        <span class="result-value">{{ $ticket->perangkat }}</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Kerusakan</span>
                        <span class="result-value">{{ $ticket->kendala }}</span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Prediksi Komponen</span>
                        <span class="result-value"><span class="badge">{{ $ticket->estimasi_sparepart }}</span></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Total Biaya</span>
                        <span class="result-value">
                            @if($ticket->status == 'selesai')
                                <span class="cost-final">Rp {{ number_format($ticket->biaya_final, 0, ',', '.') }}</span>
                                <span class="cost-note">Biaya final</span>
                            @else
                                <span class="cost-estimate">Rp {{ number_format($ticket->estimasi_biaya, 0, ',', '.') }}</span>
                                <span class="cost-note">Estimasi awal AI</span>
                            @endif
                        </span>
                    </div>
                </div>
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
