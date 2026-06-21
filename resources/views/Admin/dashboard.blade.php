@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas sistem')

@section('content')

<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#2563eb;">{{ $totalUser }}</div>
            <div class="stat-label">AKUN AKTIF</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;">
            <svg fill="none" stroke="#ca8a04" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#ca8a04;">{{ $pendaftarBaru }}</div>
            <div class="stat-label">PENDAFTAR MENUNGGU VALIDASI</div>
        </div>
        @if($pendaftarBaru > 0)
        <a href="{{ route('admin.pendaftar.index') }}"
           style="font-size:12px;color:#2563eb;text-decoration:none;font-weight:500;">
            Validasi sekarang →
        </a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">
            <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#16a34a;">{{ $totalPeminjaman }}</div>
            <div class="stat-label">TOTAL PEMINJAMAN</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;">
            <svg fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#7c3aed;">{{ $totalRuangan }}</div>
            <div class="stat-label">TOTAL RUANGAN TERSEDIA</div>
        </div>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:3fr 340px;gap:20px;align-items:start;">

        <div class="card" style="height:auto;max-height:320px;">
        <div class="card-header" style="padding-bottom:16px;">
            <div>
                <span class="card-title">Jadwal Penggunaan Ruangan Hari Ini</span>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
        </div>
        <div class="table-wrap" style="max-height:220px;overflow-y:auto;">
            <table style="table-layout:fixed;width:100%;">
                <thead>
        <tr>
            <th style="width:28%;">Ruangan</th>
            <th style="width:28%;">Ormawa</th>
            <th style="width:16%;">Waktu</th>
            <th style="width:18%;">Status</th>
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
                        <div style="font-weight:500;font-size:13px;">
                         {{ $p->ruangan->nama_ruangan ?? '-' }}
                        </div>

                    <div style="
                        font-size:11px;
                        color:var(--text-muted);
                        white-space:nowrap;
                        overflow:hidden;
                        text-overflow:ellipsis;">
                            {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}
                    </div>
                        </td>
                        <td>
                <div style="
                    font-size:13px;
                    font-weight:500;
                    white-space:nowrap;
                    overflow:hidden;
                    text-overflow:ellipsis;">
                    {{ $p->nama_ormawa }}
                </div>

                <div style="
                    font-size:11px;
                    color:var(--text-muted);
                    white-space:nowrap;
                    overflow:hidden;
                    text-overflow:ellipsis;">
                    {{ $p->keperluan }}
                </div>
                    </td>
                        <td style="font-size:13px;white-space:nowrap;">
                            {{ $mulai->format('H:i') }}–{{ $selesai->format('H:i') }}
                        </td>
                        <td>
                            <span class="badge {{ $statusLive[0] }}" style="white-space:nowrap;">
                                {{ $statusLive[1] }}
                            </span>
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
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengguna Baru
                </a>
                <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Validasi Pendaftar
                    @if($pendaftarBaru > 0)
                    <span class="badge badge-orange" style="margin-left:auto;">{{ $pendaftarBaru }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.status-peminjaman') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Lihat Status Peminjaman
                </a>
                <a href="{{ route('admin.laporan.unduh') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Unduh Laporan
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Informasi Sistem</span></div>
            <div class="card-body">
                <div class="detail-row">
                   <div class="detail-label" style="width:120px;">Versi</div>
                   <div class="detail-value" style="white-space:nowrap;">SiPinjam v1.0</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label" style="width:120px;">Framework</div>
                    <div class="detail-value">Laravel {{ app()->version() }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label" style="width:120px;">Tanggal</div>
                    <div class="detail-value">{{ now()->isoFormat('D MMM Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label" style="width:120px;">Admin</div>
                    <div class="detail-value">{{ auth()->user()->nama }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
