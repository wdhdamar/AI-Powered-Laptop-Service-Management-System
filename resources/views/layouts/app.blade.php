<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MarsTop')</title>
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
            display: flex;
            flex-direction: column;
        }
        a { color: inherit; text-decoration: none; }
        img, svg { display: block; }

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
            --white: #FFFFFF;
            --success: #15803D;
            --success-bg: #F0FDF4;
            --success-border: #BBF7D0;
            --warning: #B45309;
            --warning-bg: #FFFBEB;
            --warning-border: #FDE68A;
            --info: #1D4ED8;
            --info-bg: #EFF6FF;
            --info-border: #BFDBFE;
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
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-link {
            font-size: 13px; font-weight: 500; color: var(--slate-600);
            padding: 6px 12px; border-radius: 6px;
            transition: color .15s, background .15s;
        }
        .nav-link:hover { color: var(--slate-900); background: var(--slate-100); }
        .nav-link.active { color: var(--slate-900); background: var(--slate-100); font-weight: 600; }
        .nav-divider { width: 1px; height: 20px; background: var(--slate-200); margin: 0 4px; }

        /* ── FLOW ESCAPE HATCH ── */
        .back-home-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; color: var(--slate-400);
            margin-bottom: 16px; transition: color .15s;
        }
        .back-home-link:hover { color: var(--slate-600); }

        /* ── PAGE WRAPPER ── */
        .page-main { padding: 48px 0 80px; flex: 1; }
        .page-head { margin-bottom: 32px; }
        .page-head h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.4px; color: var(--slate-900); margin-bottom: 6px; }
        .page-head p { font-size: 14.5px; color: var(--slate-500); }

        /* ── CARDS ── */
        .card { background: #fff; border: 1px solid var(--slate-200); border-radius: 12px; padding: 32px; }

        /* ── FORM ── */
        .form-group { margin-bottom: 22px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: var(--slate-700); margin-bottom: 7px; }
        .form-control {
            width: 100%; border: 1px solid var(--slate-200);
            border-radius: 7px; padding: 10px 14px;
            font-size: 14px; font-family: inherit;
            color: var(--slate-900); background: #fff;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-control::placeholder { color: var(--slate-400); }
        .form-control:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(249,115,22,0.12); }
        .form-control.is-invalid { border-color: var(--error); }
        textarea.form-control { resize: vertical; line-height: 1.6; }
        .form-hint { font-size: 12.5px; color: var(--slate-400); margin-top: 6px; }
        .form-error { font-size: 12.5px; color: var(--error); margin-top: 6px; font-weight: 500; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 14px; font-weight: 600; font-family: inherit;
            padding: 11px 20px; border-radius: 7px;
            cursor: pointer; border: none; transition: all .15s;
        }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: var(--brand-dark); }
        .btn-primary:disabled { opacity: .65; cursor: not-allowed; }
        .btn-secondary { background: #fff; color: var(--slate-700); border: 1px solid var(--slate-200); }
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
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="site-nav" aria-label="Navigasi utama">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="nav-logo">Mars<span class="accent">Top</span></a>
            <div class="nav-links">
                <a href="/konsultasi" class="nav-link {{ request()->is('konsultasi') ? 'active' : '' }}">Konsultasi AI</a>
                <div class="nav-divider" aria-hidden="true"></div>
                <a href="/cek-status" class="nav-link {{ request()->is('cek-status') ? 'active' : '' }}">Lacak Servis</a>
            </div>
        </div>
    </div>
</nav>

@yield('content')

<footer class="site-footer" aria-label="Footer">
    <div class="container">
        <div class="footer-inner">
            <span class="footer-brand">Mars<span class="accent">Top</span></span>
            <span class="footer-copy">@yield('footer-copy', '© 2026 MarsTop Service Center')</span>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
