@extends('layouts.admin')

@section('title', 'Dashboard Teknisi — MarsTop')

@push('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header { margin-bottom: 28px; }
    .page-header h1 { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; color: var(--slate-900); }
    .page-header p { font-size: 14px; color: var(--slate-500); margin-top: 4px; }

    /* ── STATS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 28px; }
    .stat-card {
        background: #fff; border: 1px solid var(--slate-200); border-radius: 10px;
        padding: 20px 22px; border-left: 3px solid transparent;
    }
    .stat-card.units { border-left-color: var(--brand); }
    .stat-card.revenue { border-left-color: var(--success); }
    .stat-label { font-size: 11.5px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: var(--slate-400); margin-bottom: 8px; }
    .stat-value { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
    .stat-card.units .stat-value { color: var(--slate-900); }
    .stat-card.revenue .stat-value { color: var(--success); }

    /* ── FILTER TABS ── */
    .filter-bar {
        display: flex; align-items: center;
        justify-content: space-between; margin-bottom: 20px;
        flex-wrap: wrap; gap: 12px;
    }
    .filter-tabs { display: flex; gap: 4px; background: #fff; border: 1px solid var(--slate-200); border-radius: 8px; padding: 4px; }
    .filter-tab {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 500; color: var(--slate-600);
        padding: 7px 14px; border-radius: 6px;
        border: none; background: transparent; cursor: pointer;
        transition: all .15s; white-space: nowrap;
    }
    .filter-tab:hover { color: var(--slate-900); background: var(--slate-100); }
    .filter-tab.active { background: var(--slate-900); color: #fff; font-weight: 600; }
    .tab-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 20px; height: 20px; padding: 0 6px;
        border-radius: 20px; font-size: 11px; font-weight: 700;
    }
    .filter-tab .tab-count { background: rgba(255,255,255,0.15); color: inherit; }
    .filter-tab:not(.active) .tab-count { background: var(--slate-100); color: var(--slate-500); }
    .filter-tab.active .tab-count { background: rgba(255,255,255,0.2); color: #fff; }
    .filter-info { font-size: 13px; color: var(--slate-400); }
    .filter-info strong { color: var(--slate-600); }

    /* ── TABLE CARD ── */
    .table-card { background: #fff; border: 1px solid var(--slate-200); border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 18px 24px; border-bottom: 1px solid var(--slate-100); }
    .table-card-header h2 { font-size: 14.5px; font-weight: 600; color: var(--slate-900); }
    .table-wrap { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--slate-50); border-bottom: 1px solid var(--slate-200); }
    thead th {
        padding: 12px 16px; font-size: 11.5px; font-weight: 600;
        letter-spacing: 0.05em; text-transform: uppercase;
        color: var(--slate-500); text-align: left; white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid var(--slate-100); transition: background .12s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--slate-50); }
    tbody td { padding: 14px 16px; font-size: 13.5px; vertical-align: top; }

    .td-date { color: var(--slate-500); font-size: 13px; }
    .td-code {
        display: inline-block; margin-top: 4px;
        font-family: 'Courier New', monospace; font-size: 11.5px;
        font-weight: 700; letter-spacing: 0.5px;
        background: var(--slate-100); color: var(--slate-600);
        border-radius: 4px; padding: 2px 7px;
    }
    .td-device { font-weight: 600; color: var(--slate-900); }
    .td-raw { font-size: 12.5px; color: var(--slate-500); font-style: italic; max-width: 220px; }
    .td-kendala { font-weight: 500; color: var(--slate-800); margin-bottom: 6px; font-size: 13px; }

    /* STATUS BADGES */
    .badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11.5px; font-weight: 600;
        border-radius: 20px; padding: 3px 10px; white-space: nowrap;
    }
    .badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
    .badge-waiting { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-border); }
    .badge-waiting .badge-dot { background: var(--warning); }
    .badge-progress { background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border); }
    .badge-progress .badge-dot { background: var(--info); }
    .badge-done { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
    .badge-done .badge-dot { background: var(--success); }

    /* SPAREPART BADGE */
    .spare-badge {
        display: inline-flex; margin-top: 5px;
        font-size: 11.5px; font-weight: 600;
        background: var(--warning-bg); color: var(--warning-dark);
        border: 1px solid var(--warning-border);
        border-radius: 5px; padding: 2px 8px;
    }
    .td-estbio { font-size: 12px; color: var(--slate-400); margin-top: 5px; }

    /* STATUS ACTION COLUMN */
    .action-col { min-width: 190px; }
    .action-col .badge { margin-bottom: 10px; }

    /* ACTION BUTTONS */
    .btn-action {
        display: inline-flex; align-items: center; gap: 6px;
        width: 100%; justify-content: center;
        font-size: 12.5px; font-weight: 600; font-family: inherit;
        padding: 8px 14px; border-radius: 6px;
        cursor: pointer; border: none; transition: all .15s;
    }
    .btn-action-blue { background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border); }
    .btn-action-blue:hover { background: var(--info); color: #fff; }

    /* FINISH FORM */
    .finish-form {
        background: var(--slate-50); border: 1px solid var(--slate-200);
        border-radius: 8px; padding: 12px;
    }
    .finish-label { font-size: 12px; font-weight: 500; color: var(--slate-600); margin-bottom: 6px; display: block; }
    .finish-input {
        width: 100%; border: 1px solid var(--slate-200); border-radius: 6px;
        padding: 8px 10px; font-size: 13px; font-family: inherit;
        color: var(--slate-900); background: #fff; outline: none;
        transition: border-color .15s, box-shadow .15s; margin-bottom: 8px;
    }
    .finish-input::placeholder { color: var(--slate-400); }
    .finish-input:focus { border-color: var(--success); box-shadow: 0 0 0 3px rgba(21,128,61,0.1); }
    .btn-finish {
        width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
        font-size: 12.5px; font-weight: 600; font-family: inherit;
        padding: 9px; border-radius: 6px;
        background: var(--success); color: #fff;
        border: none; cursor: pointer; transition: background .15s;
    }
    .btn-finish:hover { background: var(--success-dark); }

    /* DONE INFO */
    .done-cost { font-weight: 700; color: var(--success); font-size: 15px; margin-top: 6px; }
    .done-note { font-size: 12px; color: var(--slate-400); margin-top: 2px; }

    /* EMPTY STATE */
    .empty-row td {
        text-align: center; padding: 56px 24px;
        color: var(--slate-400); font-size: 14px;
    }
    .empty-icon { margin: 0 auto 14px; color: var(--slate-300); }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .filter-tabs { overflow-x: auto; }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .filter-bar { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<!-- FLASH: SUCCESS UPDATE -->
@if(session('success_update'))
    <div class="toast toast-success" role="status" aria-live="polite">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="8" cy="8" r="7"/><path d="M5 8l2 2 4-4"/>
        </svg>
        {{ session('success_update') }}
    </div>
@endif

<!-- PAGE HEADER -->
<div class="page-header">
    <h1>Dashboard Teknisi</h1>
    <p>Pantau antrean, kebutuhan sparepart, dan estimasi biaya dari AI.</p>
</div>

<!-- STATS -->
<div class="stats-grid" aria-label="Ringkasan">
    <div class="stat-card units">
        <div class="stat-label">Total Antrean Aktif</div>
        <div class="stat-value">{{ $counts['semua'] }} unit</div>
    </div>
    <div class="stat-card revenue">
        <div class="stat-label">Potensi Pendapatan (Selesai)</div>
        <div class="stat-value">Rp {{ number_format($counts['total_pendapatan'], 0, ',', '.') }}</div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="filter-bar">
    <div class="filter-tabs" role="tablist" aria-label="Filter status">
        <a href="/admin" class="filter-tab {{ request('status') == null ? 'active' : '' }}" role="tab" aria-selected="{{ request('status') == null ? 'true' : 'false' }}">
            Semua
            <span class="tab-count">{{ $counts['semua'] }}</span>
        </a>
        <a href="/admin?status=antrean" class="filter-tab {{ request('status') == 'antrean' ? 'active' : '' }}" role="tab" aria-selected="{{ request('status') == 'antrean' ? 'true' : 'false' }}">
            Menunggu
            <span class="tab-count">{{ $counts['antrean'] }}</span>
        </a>
        <a href="/admin?status=sedang_diperbaiki" class="filter-tab {{ request('status') == 'sedang_diperbaiki' ? 'active' : '' }}" role="tab" aria-selected="{{ request('status') == 'sedang_diperbaiki' ? 'true' : 'false' }}">
            Dikerjakan
            <span class="tab-count">{{ $counts['sedang_diperbaiki'] }}</span>
        </a>
        <a href="/admin?status=selesai" class="filter-tab {{ request('status') == 'selesai' ? 'active' : '' }}" role="tab" aria-selected="{{ request('status') == 'selesai' ? 'true' : 'false' }}">
            Selesai
            <span class="tab-count">{{ $counts['selesai'] }}</span>
        </a>
    </div>
    <p class="filter-info">Total tiket: <strong>{{ $counts['semua'] }}</strong></p>
</div>

<!-- TABLE -->
<div class="table-card">
    <div class="table-card-header">
        <h2>Daftar Order Servis</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal / Kode</th>
                    <th>Perangkat</th>
                    <th>Keluhan Pelanggan</th>
                    <th>Analisa AI</th>
                    <th>Status &amp; Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $item)
                <tr>
                    <!-- Tanggal / Kode -->
                    <td>
                        <div class="td-date">{{ date('d M Y, H:i', strtotime($item->created_at)) }}</div>
                        @if($item->kode_booking)
                            <span class="td-code">{{ $item->kode_booking }}</span>
                        @else
                            <span class="td-code" style="color:#94A3B8;">KONSULTASI</span>
                        @endif
                    </td>

                    <!-- Perangkat -->
                    <td>
                        <span class="td-device">{{ $item->perangkat }}</span>
                    </td>

                    <!-- Keluhan -->
                    <td>
                        <div class="td-raw">&ldquo;{{ $item->raw_text }}&rdquo;</div>
                    </td>

                    <!-- Analisa AI -->
                    <td>
                        <div class="td-kendala">{{ $item->kendala }}</div>
                        <span class="spare-badge">{{ $item->estimasi_sparepart }}</span>
                        <div class="td-estbio">Est. Rp {{ number_format($item->estimasi_biaya, 0, ',', '.') }}</div>
                    </td>

                    <!-- Status & Aksi -->
                    <td class="action-col">
                        @if($item->status == 'antrean')
                            <span class="badge badge-waiting">
                                <span class="badge-dot"></span>
                                Menunggu Perangkat
                            </span>
                            <form action="/admin/update-status/{{ $item->id }}" method="POST" style="margin:0;">
                                @csrf
                                <input type="hidden" name="status" value="sedang_diperbaiki">
                                <button type="submit" class="btn-action btn-action-blue">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9.5 1.5a2 2 0 012.3 3.3L5 11.5 2.5 10.5l-.5-2.5 7-6.5z"/>
                                    </svg>
                                    Mulai Servis
                                </button>
                            </form>

                        @elseif($item->status == 'sedang_diperbaiki')
                            <span class="badge badge-progress">
                                <span class="badge-dot"></span>
                                Sedang Dikerjakan
                            </span>
                            <div class="finish-form">
                                <form action="/admin/update-status/{{ $item->id }}" method="POST" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="status" value="selesai">
                                    <label for="biaya_{{ $item->id }}" class="finish-label">Biaya final (Rupiah):</label>
                                    <input
                                        type="number" name="biaya_final" id="biaya_{{ $item->id }}"
                                        class="finish-input" placeholder="Contoh: 450000"
                                        min="0" required
                                    >
                                    <button type="submit" class="btn-finish">
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 6.5l3.5 3.5 5.5-6"/>
                                        </svg>
                                        Tandai Selesai
                                    </button>
                                </form>
                            </div>

                        @elseif($item->status == 'selesai')
                            <span class="badge badge-done">
                                <span class="badge-dot"></span>
                                Siap Diambil
                            </span>
                            <div class="done-cost">Rp {{ number_format($item->biaya_final, 0, ',', '.') }}</div>
                            <div class="done-note">Biaya final</div>

                        @else
                            <span style="font-size:12px; color:#94A3B8;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="5">
                        <div class="empty-icon" aria-hidden="true">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="6" y="10" width="28" height="24" rx="2"/>
                                <path d="M13 6h14M20 18v6M17 21h6"/>
                            </svg>
                        </div>
                        Belum ada data servis yang masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
