<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Portal Teknisi MarsTop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0F172A; background: #F8FAFC;
            line-height: 1.6; -webkit-font-smoothing: antialiased;
            display: flex; flex-direction: column;
        }
        a { color: inherit; text-decoration: none; }
        svg { display: block; }

        :root {
            --brand: #F97316; --brand-dark: #EA580C; --brand-light: #FFF7ED;
            --slate-900: #0F172A; --slate-800: #1E293B; --slate-700: #334155;
            --slate-600: #475569; --slate-500: #64748B; --slate-400: #94A3B8;
            --slate-300: #CBD5E1; --slate-200: #E2E8F0; --slate-100: #F1F5F9;
            --slate-50: #F8FAFC;
            --error: #DC2626; --error-bg: #FEF2F2; --error-border: #FECACA;
        }

        /* ── WRAPPER ── */
        .page {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* ── LEFT BRAND PANEL ── */
        .brand-panel {
            background: var(--slate-900);
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .brand-logo { font-size: 22px; font-weight: 700; color: #fff; letter-spacing: -0.4px; }
        .brand-logo .accent { color: var(--brand); }
        .brand-tagline {
            margin-top: auto;
            padding-top: 40px;
        }
        .brand-tagline h2 {
            font-size: 28px; font-weight: 700;
            line-height: 1.25; letter-spacing: -0.5px;
            color: #fff; margin-bottom: 14px;
        }
        .brand-tagline h2 span { color: var(--brand); }
        .brand-tagline p { font-size: 15px; color: var(--slate-400); line-height: 1.65; max-width: 340px; }
        .brand-features { margin-top: 40px; display: flex; flex-direction: column; gap: 14px; }
        .brand-feat { display: flex; align-items: center; gap: 12px; }
        .brand-feat-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(249,115,22,0.12); color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-feat-text { font-size: 13.5px; color: var(--slate-300); }
        .brand-footer { font-size: 12.5px; color: var(--slate-600); }

        /* ── RIGHT FORM PANEL ── */
        .form-panel {
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            padding: 48px 40px;
        }
        .login-box { width: 100%; max-width: 360px; }

        .login-heading { margin-bottom: 28px; }
        .login-heading h1 { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; color: var(--slate-900); margin-bottom: 6px; }
        .login-heading p { font-size: 14px; color: var(--slate-500); }

        /* ── ERROR ── */
        .alert-error {
            padding: 12px 14px; border-radius: 8px;
            background: var(--error-bg); border: 1px solid var(--error-border);
            color: var(--error); font-size: 13.5px; font-weight: 500;
            margin-bottom: 22px; display: flex; gap: 10px; align-items: center;
        }
        .alert-error svg { flex-shrink: 0; }

        /* ── FORM ── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13.5px; font-weight: 500; color: var(--slate-700); margin-bottom: 6px; }
        .form-control {
            width: 100%; border: 1px solid var(--slate-200); border-radius: 7px;
            padding: 10px 14px; font-size: 14px; font-family: inherit;
            color: var(--slate-900); background: #fff; outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control::placeholder { color: var(--slate-400); }
        .form-control:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(249,115,22,0.12); }

        .btn-submit {
            width: 100%; padding: 12px;
            background: var(--brand); color: #fff;
            font-size: 14px; font-weight: 600; font-family: inherit;
            border: none; border-radius: 7px; cursor: pointer;
            transition: background .15s;
            margin-top: 6px;
        }
        .btn-submit:hover { background: var(--brand-dark); }

        .back-link {
            display: block; text-align: center;
            margin-top: 20px; font-size: 13px;
            color: var(--slate-400); transition: color .15s;
        }
        .back-link:hover { color: var(--slate-600); }

        a:focus-visible, button:focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .page { grid-template-columns: 1fr; }
            .brand-panel { display: none; }
            .form-panel { padding: 40px 24px; align-items: flex-start; padding-top: 60px; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- LEFT: Brand -->
    <div class="brand-panel">
        <div class="brand-logo">Mars<span class="accent">Top</span></div>

        <div class="brand-tagline">
            <h2>Portal <span>Teknisi</span><br>MarsTop</h2>
            <p>Kelola antrean servis, pantau status perangkat, dan input biaya final dari satu dasbor terpadu.</p>

            <div class="brand-features">
                <div class="brand-feat">
                    <div class="brand-feat-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2L6 9.5M10.5 2H14v3.5M7 4H3a1 1 0 00-1 1v8a1 1 0 001 1h8a1 1 0 001-1V9"/>
                        </svg>
                    </div>
                    <span class="brand-feat-text">Kelola semua antrean servis masuk</span>
                </div>
                <div class="brand-feat">
                    <div class="brand-feat-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 1v5l3 2M8 15A7 7 0 108 1a7 7 0 000 14z"/>
                        </svg>
                    </div>
                    <span class="brand-feat-text">Update status servis secara real-time</span>
                </div>
                <div class="brand-feat">
                    <div class="brand-feat-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 5l6-3 6 3v6l-6 3-6-3V5zM8 2v12M2 5l6 3 6-3"/>
                        </svg>
                    </div>
                    <span class="brand-feat-text">Input biaya final dan notifikasi otomatis</span>
                </div>
            </div>
        </div>

        <p class="brand-footer">&copy; 2026 MarsTop Service Center</p>
    </div>

    <!-- RIGHT: Form -->
    <div class="form-panel">
        <div class="login-box">
            <div class="login-heading">
                <h1>Masuk ke Sistem</h1>
                <p>Gunakan akun teknisi atau admin untuk melanjutkan.</p>
            </div>

            <x-flash key="error" />

            <form action="/admin/login" method="POST" novalidate>
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Staff</label>
                    <input
                        type="email" name="email" id="email"
                        class="form-control"
                        placeholder="nama@marstop.com"
                        required autocomplete="email"
                    >
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password" name="password" id="password"
                        class="form-control"
                        placeholder="••••••••"
                        required autocomplete="current-password"
                    >
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <a href="/" class="back-link">
                &larr; Kembali ke halaman publik
            </a>
        </div>
    </div>

</div>
</body>
</html>
