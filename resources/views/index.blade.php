@extends('layouts.app')

@section('title', 'Konsultasi AI — MarsTop')

@push('styles')
<style>
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

    @media (max-width: 640px) {
        .result-row { flex-direction: column; gap: 4px; }
        .result-label { flex: none; }
        .result-footer { flex-direction: column-reverse; }
        .btn-full-mob { width: 100%; justify-content: center; }
        .si-label { display: none; }
        .success-actions { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<main class="page-main">
    <div class="container narrow">

        <a href="/" class="back-home-link">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 3L4 7l4.5 4"/></svg>
            Kembali ke Beranda
        </a>

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

            <x-flash key="error" />

            <div class="card">
                <form action="/kirim-aduan" method="POST" novalidate id="aduan-form">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input
                            type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        >
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @else
                            <p class="form-hint">Kode booking akan dikirimkan ke email ini setelah konfirmasi.</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="aduan" class="form-label">Ceritakan Keluhan Laptop Anda</label>
                        <textarea
                            class="form-control @error('aduan') is-invalid @enderror" id="aduan" name="aduan"
                            rows="5"
                            placeholder="Contoh: Laptop Asus saya mendadak mati total saat dicolok charger. Layarnya tiba-tiba hitam dan tidak bisa dinyalakan lagi..."
                            required
                        >{{ old('aduan') }}</textarea>
                        @error('aduan')
                            <p class="form-error">{{ $message }}</p>
                        @else
                            <p class="form-hint">Gunakan bahasa sehari-hari. Semakin detail, semakin akurat analisa AI.</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-full" id="aduan-submit">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M11.5 2.5L4 9h5l-1 4.5 6-7H9z"/>
                        </svg>
                        <span id="aduan-submit-label">Analisa dengan AI</span>
                    </button>
                </form>
            </div>

        @endif

    </div>
</main>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('aduan-form');
        if (!form) return;
        form.addEventListener('submit', function () {
            var btn = document.getElementById('aduan-submit');
            var label = document.getElementById('aduan-submit-label');
            btn.disabled = true;
            label.textContent = 'Menganalisa...';
        });
    })();
</script>
@endpush
