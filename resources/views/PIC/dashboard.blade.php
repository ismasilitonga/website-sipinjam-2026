@extends('layouts.pic')

@section('title', 'Dashboard PIC')
@section('subtitle', 'Ringkasan aktivitas & pengajuan masuk')

@section('topbar-action')
<a href="{{ route('pic.daftar-pengajuan') }}" class="btn btn-primary btn-sm">        
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Validasi Pengajuan Ruangan
        @if($menungguValidasi > 0)
            <span style="background:rgba(255,255,255,.25);color:#fff;font-size:11px;
                         font-weight:700;padding:1px 7px;border-radius:999px;margin-left:2px;">
                {{ $menungguValidasi }}
            </span>
        @endif
    </a>
@endsection

@section('content')

<div class="stat-grid">
    <div class="stat-card" style="{{ $menungguValidasi > 0 ? 'border-color:#ddd6fe;' : '' }}">
        <div class="stat-icon" style="background:#ede9fe;">
            <svg fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#7c3aed;">{{ $menungguValidasi }}</div>
            <div class="stat-label">PENGAJUAN MENUNGGU VALIDASI</div>
        </div>
        @if($menungguValidasi > 0)
        <a href="{{ route('pic.daftar-pengajuan') }}"
           style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">
            Validasi sekarang →
        </a>
        @endif
    </div>

    <div class="stat-card" style="{{ $insidenAktif > 0 ? 'border-color:#fca5a5;' : '' }}">
        <div class="stat-icon" style="background:#fee2e2;">
            <svg fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#dc2626;">{{ $insidenAktif }}</div>
            <div class="stat-label">INSIDEN AKTIF (BELUM SELESAI)</div>
        </div>
        @if($insidenAktif > 0)
        <a href="{{ route('pic.laporan-insiden') }}"
           style="font-size:12px;color:var(--danger);text-decoration:none;font-weight:600;">
            Tindak lanjuti →
        </a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg fill="none" stroke="#1d4ed8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#1d4ed8;">{{ $totalRuangan }}</div>
            <div class="stat-label">TOTAL RUANGAN</div>
        </div>
        <a href="{{ route('pic.ruangan.index') }}"
           style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">
            Kelola ruangan →
        </a>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">
            <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#16a34a;">{{ $totalBarang }}</div>
            <div class="stat-label">TOTAL BARANG</div>
        </div>
        <a href="{{ route('pic.barang.index') }}"
           style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">
            Kelola barang →
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    <div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="padding-bottom:16px;">
        <div>
            <span class="card-title">Jadwal Penggunaan Ruangan Hari Ini</span>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                {{ now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Ruangan</th>
                    <th>Ormawa</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ruanganAktifHariIni as $p)
                @php
                    $mulai   = \Carbon\Carbon::parse($p->tanggal_mulai);
                    $selesai = \Carbon\Carbon::parse($p->tanggal_selesai);

                    $statusLive = match($p->status) {
                   'disetujui' => ['badge-yellow', '🟡 Akan Digunakan'],
                   'berjalan'  => ['badge-green', '🟢 Sedang Digunakan'],
                   'selesai'   => ['badge-gray', '⚪ Selesai'],
                    default     => ['badge-gray', $p->status],
                    };
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $p->ruangan->nama ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">{{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}</div>
                    </td>
                    <td style="font-size:13px;">
                        <div style="font-weight:500;">{{ $p->nama_ormawa }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">{{ $p->keperluan }}</div>
                    </td>
                    <td style="font-size:13px;white-space:nowrap;">
                        {{ $mulai->format('H:i') }}–{{ $selesai->format('H:i') }}
                    </td>
                    <td>
                        <span class="badge {{ $statusLive[0] }}" style="white-space:nowrap;">{{ $statusLive[1] }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state" style="padding:30px 20px;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p>Tidak ada ruangan yang digunakan hari ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Aksi Cepat</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:9px;">
                <a href="{{ route('pic.ruangan.create') }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Ruangan
                </a>
                <a href="{{ route('pic.barang.create') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Barang
                </a>
                <a href="{{ route('pic.serah-terima') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Serah Terima Barang
                </a>
                <a href="{{ route('pic.laporan-insiden') }}" class="btn btn-outline"
                   style="{{ $insidenAktif > 0 ? 'color:var(--danger);border-color:#fca5a5;' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Laporan Insiden
                    @if($insidenAktif > 0)
                        <span class="badge badge-red" style="margin-left:auto;">{{ $insidenAktif }}</span>
                    @endif
                </a>
                <a href="{{ route('pic.laporan.unduh') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Unduh Laporan
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
