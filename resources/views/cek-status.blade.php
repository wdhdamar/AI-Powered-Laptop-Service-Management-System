@extends('layouts.app')

@section('title', 'Lacak Status Servis — MarsTop')

@push('styles')
<style>
    .narrow { max-width: 600px; }
    .card { padding: 0; }
    .card-body { padding: 28px; }

    /* FORM */
    .input-group { display: flex; gap: 10px; }
    .form-control.mono { font-family: 'Courier New', monospace; letter-spacing: 1.5px; font-size: 15px; }
    .btn { white-space: nowrap; }

    /* ERROR (inline, below the search form) */
    .card .alert-error { margin-top: 16px; margin-bottom: 0; }

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

    @media (max-width: 640px) {
        .input-group { flex-direction: column; }
        .result-row { flex-direction: column; gap: 4px; }
        .result-label { flex: none; }
        .stepper-label { display: none; }
    }
</style>
@endpush

@section('nav-links')
    <a href="/konsultasi" class="nav-link">Konsultasi AI</a>
    <div class="nav-divider" aria-hidden="true"></div>
    <a href="/admin" class="nav-link">Portal Teknisi</a>
@endsection

@section('content')
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
                            class="form-control mono @error('kode_booking') is-invalid @enderror"
                            placeholder="SRV-2026-0001"
                            value="{{ old('kode_booking', $kodeBooking ?? '') }}"
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
                    @error('kode_booking')
                        <p class="form-error">{{ $message }}</p>
                    @else
                        <p id="kode-hint" class="form-hint">Format: SRV-TAHUN-NOMOR (contoh: SRV-2026-0001)</p>
                    @enderror
                </form>

                <x-flash key="error_cari" />
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
@endsection
