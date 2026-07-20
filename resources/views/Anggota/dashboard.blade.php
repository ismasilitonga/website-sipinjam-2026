@extends('layouts.anggota')

@section('title', 'Dashboard')
@section('subtitle', "Selamat datang, " . auth()->user()->nama)

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef2ff;">
            <svg fill="none" stroke="#4f46e5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#4f46e5;">{{ $totalPeminjaman }}</div>
            <div class="stat-label">TOTAL PEMINJAMAN SAYA</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#ffedd5;">
            <svg fill="none" stroke="#ea580c" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#ea580c;">{{ $totalInsiden }}</div>
            <div class="stat-label">LAPORAN INSIDEN</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;">
            <svg fill="none" stroke="#16a34a" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#16a34a;">
                {{ $disetujui }}
            </div>
            <div class="stat-label">PEMINJAMAN DISETUJUI</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;">
            <svg fill="none" stroke="#ca8a04" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#ca8a04;">
                {{ $menunggu }}
            </div>
            <div class="stat-label">MENUNGGU PERSETUJUAN</div>
        </div>
    </div>
</div>

<div class="hscroll-row" style="align-items:flex-start;">

    <div class="card" style="flex:1 1 0; min-width:340px;">
        <div class="card-header" style="padding-bottom:12px;">
            <div>
                <span class="card-title">Jadwal Penggunaan Ruangan Hari Ini</span>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
            <a href="{{ route('anggota.riwayat-ruangan') }}" style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">Lihat semua →</a>
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
                        $checkInHariIni = $p->checkIns->firstWhere('tanggal', today()->toDateString());

                    if ($checkInHariIni && $checkInHariIni->status_verifikasi === 'ditolak') {
                        $statusLive = ['badge-yellow', '🟡 Akan Digunakan'];
                    } elseif ($checkInHariIni && is_null($checkInHariIni->waktu_checkout)) {
                        $statusLive = ['badge-green', '🟢 Sedang Digunakan'];
                    } elseif ($checkInHariIni && $checkInHariIni->waktu_checkout) {
                        $statusLive = ['badge-gray', '⚪ Selesai Hari Ini'];
                    } elseif ($p->status === 'selesai') {
                        $statusLive = ['badge-gray', '⚪ Selesai'];
                    } else {
                        $statusLive = ['badge-yellow', '🟡 Akan Digunakan'];
                    }
                @endphp
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
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
                            <div class="empty-state" style="padding:16px 20px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:26px;height:26px;margin-bottom:6px;">
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

    <div class="card" style="flex:0 0 300px; min-width:260px;">
        <div class="card-header">
            <span class="card-title">Aksi Cepat</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:9px;">
            <a href="{{ route('anggota.pengajuan-ruangan') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajukan Ruangan
            </a>
            <a href="{{ route('anggota.pengajuan-barang') }}" class="btn btn-cyan" style="color:#fff;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Ajukan Barang
            </a>
            <a href="{{ route('anggota.checkin') }}" class="btn btn-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                </svg>
                Check-in Sekarang
            </a>
            <a href="{{ route('anggota.checkout') }}" class="btn btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
                Check-out
            </a>
            <a href="{{ route('anggota.lapor-insiden') }}" class="btn btn-outline" style="color:var(--danger);border-color:#fecaca;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Lapor Insiden
            </a>
        </div>
    </div>
</div>

@endsection