<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Teknisi — MarsTop')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0F172A; background: #F8FAFC;
            line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        svg { display: block; }
        button { font-family: inherit; }

        :root {
            --brand: #F97316; --brand-dark: #EA580C; --brand-light: #FFF7ED;
            --slate-900: #0F172A; --slate-800: #1E293B; --slate-700: #334155;
            --slate-600: #475569; --slate-500: #64748B; --slate-400: #94A3B8;
            --slate-300: #CBD5E1; --slate-200: #E2E8F0; --slate-100: #F1F5F9;
            --slate-50: #F8FAFC; --white: #FFFFFF;
            --success: #15803D; --success-bg: #F0FDF4; --success-border: #BBF7D0; --success-dark: #14532D;
            --warning: #B45309; --warning-bg: #FFFBEB; --warning-border: #FDE68A; --warning-dark: #78350F;
            --info: #1D4ED8; --info-bg: #EFF6FF; --info-border: #BFDBFE;
            --error: #DC2626; --error-bg: #FEF2F2; --error-border: #FECACA;
        }

        /* ── LAYOUT ── */
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }

        /* ── ADMIN NAV ── */
        .admin-nav {
            background: var(--slate-900); border-bottom: 1px solid var(--slate-800);
            position: sticky; top: 0; z-index: 50;
        }
        .admin-nav-inner {
            display: flex; align-items: center;
            justify-content: space-between; height: 56px; gap: 16px;
        }
        .admin-logo { font-size: 16px; font-weight: 700; color: #fff; letter-spacing: -0.3px; display: flex; align-items: center; gap: 10px; }
        .admin-logo .accent { color: var(--brand); }
        .admin-badge {
            font-size: 10.5px; font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase; color: var(--slate-400);
            background: var(--slate-800); border: 1px solid var(--slate-700);
            border-radius: 4px; padding: 2px 8px;
        }
        .admin-nav-actions { display: flex; align-items: center; gap: 8px; }
        .btn-nav-ghost {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 500; color: var(--slate-400);
            padding: 6px 12px; border-radius: 6px; background: transparent;
            border: 1px solid transparent; cursor: pointer;
            transition: color .15s, background .15s, border-color .15s;
        }
        .btn-nav-ghost:hover { color: #fff; background: var(--slate-800); border-color: var(--slate-700); }
        .btn-nav-danger {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 500; color: #FCA5A5;
            padding: 6px 12px; border-radius: 6px; background: transparent;
            border: 1px solid rgba(239,68,68,0.25); cursor: pointer;
            transition: all .15s;
        }
        .btn-nav-danger:hover { color: #fff; background: var(--error); border-color: var(--error); }

        /* ── PAGE WRAPPER ── */
        .page-content { padding: 32px 0 64px; }

        /* ── FLASH TOAST ── */
        .toast {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;
            font-size: 13.5px; font-weight: 500;
        }
        .toast-success { background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); }

        a:focus-visible, button:focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }

        @media (max-width: 640px) {
            .admin-badge { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ADMIN NAV -->
<nav class="admin-nav" aria-label="Admin navigasi">
    <div class="container">
        <div class="admin-nav-inner">
            <div class="admin-logo">
                Mars<span class="accent">Top</span>
                <span class="admin-badge">Portal Teknisi</span>
            </div>
            <div class="admin-nav-actions">
                <a href="/" class="btn-nav-ghost">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 7l5-5 5 5M3 6.5V12h3V9h2v3h3V6.5"/>
                    </svg>
                    Halaman Depan
                </a>
                <form action="/admin/logout" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-nav-danger">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 2H3a1 1 0 00-1 1v8a1 1 0 001 1h2M9 10l3-3-3-3M12 7H5"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="page-content">
    <div class="container">
        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
