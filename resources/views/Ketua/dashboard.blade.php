@extends('layouts.ketua')

@section('title', 'Dashboard Ketua')
@section('subtitle', 'Ringkasan aktivitas ormawa ' . auth()->user()->organisasi)
@section('topbar-action')
<a href="{{ route('ketua.daftar-pengajuan') }}" class="btn btn-primary btn-sm">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Kelola Pengajuan
    @if($menungguPersetujuan > 0)
        <span style="background:#fff;color:var(--accent);font-size:11px;font-weight:700;
                     padding:1px 7px;border-radius:999px;margin-left:2px;">
            {{ $menungguPersetujuan }}
        </span>
    @endif
</a>
@endsection

@section('content')

<div class="stat-grid">

    <div class="stat-card" style="{{ $menungguPersetujuan > 0 ? 'border-color:#fde68a;' : '' }}">
        <div class="stat-icon" style="background:#fef9c3;">
            <svg fill="none" stroke="#ca8a04" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#ca8a04;">{{ $menungguPersetujuan }}</div>
            <div class="stat-label">MENUNGGU PERSETUJUAN KAMU</div>
        </div>
        @if($menungguPersetujuan > 0)
        <a href="{{ route('ketua.daftar-pengajuan') }}"
           style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">
            Tinjau sekarang →
        </a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#ecfeff;">
            <svg fill="none" stroke="#0891b2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#0891b2;">
                {{ $diteruskanPic }}
            </div>
            <div class="stat-label">DITERUSKAN KE PIC</div>
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
                {{ $disetujuiAktif }}
            </div>
            <div class="stat-label">DISETUJUI (AKTIF)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#eef2ff;">
            <svg fill="none" stroke="#4f46e5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#4f46e5;">{{ $totalPengajuan }}</div>
            <div class="stat-label">TOTAL PENGAJUAN</div>
        </div>
    </div>
</div>

<div class="hscroll-row" style="align-items:flex-start;">

    <div class="card" style="flex:1 1 0; min-width:340px;">
        <div class="card-header" style="padding-bottom:10px;">
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

                        $badgeMap = [
                            'sedang_digunakan' => ['badge-green',  '🟢 Sedang Digunakan'],
                            'selesai_hari_ini' => ['badge-gray',   '⚪ Selesai Hari Ini'],
                            'selesai'          => ['badge-gray',   '⚪ Selesai'],
                            'akan_digunakan'   => ['badge-yellow', '🟡 Akan Digunakan'],
                        ];
                        $statusLive = $badgeMap[$p->status_hari_ini];
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

    <div class="hscroll-col" style="flex:0 0 300px; min-width:260px; display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Informasi Ormawa</span></div>
            <div class="card-body" style="padding:0;">
                <div class="detail-row">
                    <div class="detail-label">Organisasi</div>
                    <div class="detail-value" style="font-weight:600;">{{ auth()->user()->organisasi }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ketua</div>
                    <div class="detail-value">{{ auth()->user()->nama }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">NIM</div>
                    <div class="detail-value" style="font-family:monospace;">{{ auth()->user()->nim }}</div>
                </div>
            </div>
        </div>

        @if($menungguPersetujuan > 0)
        <div class="card" style="border-color:#fde68a;">
            <div class="card-body" style="text-align:center;padding:20px;">
                <div style="font-size:32px;margin-bottom:8px;">⏳</div>
                <div style="font-family:'Sora',sans-serif;font-size:15px;font-weight:700;color:#92400e;">
                    {{ $menungguPersetujuan }} Pengajuan
                </div>
                <div style="font-size:12.5px;color:var(--text-muted);margin:4px 0 14px;">
                    menunggu persetujuan kamu
                </div>
                <a href="{{ route('ketua.daftar-pengajuan') }}" class="btn btn-primary"
                   style="width:100%;justify-content:center;">
                    Tinjau Sekarang
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection