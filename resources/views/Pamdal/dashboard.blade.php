@extends('layouts.pamdal')

@section('title', 'Dashboard Pamdal')
@section('subtitle', 'Peminjaman aktif & status kunci hari ini')

@section('topbar-action')
    <a href="{{ route('pamdal.daftar-peminjaman') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
        Manajemen Kunci
    </a>
@endsection

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;">
            <svg fill="none" stroke="#1d4ed8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#1d4ed8;">{{ $peminjamansHariIni->count() }}</div>
            <div class="stat-label">PEMINJAMAN HARI INI</div>
        </div>
    </div>

    <div class="stat-card" style="{{ $menungguKunci > 0 ? 'border-color:#fca5a5;' : '' }}">
        <div class="stat-icon" style="background:#fee2e2;">
            <svg fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#dc2626;">{{ $menungguKunci }}</div>
            <div class="stat-label">KUNCI BELUM DIAMBIL</div>
        </div>
        @if($menungguKunci > 0)
        <a href="{{ route('pamdal.daftar-peminjaman') }}"
           style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:600;">
            Proses sekarang →
        </a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3;">
            <svg fill="none" stroke="#ca8a04" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#ca8a04;">
                {{ $peminjamansHariIni->whereNotNull('waktu_kunci_diambil')->count() }}
            </div>
            <div class="stat-label">KUNCI SUDAH DIAMBIL</div>
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
                {{ $peminjamansHariIni->whereNotNull('waktu_kunci_dikembalikan')->count() }}
            </div>
            <div class="stat-label">KUNCI SUDAH DIKEMBALIKAN</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">📋 Daftar Peminjaman Hari Ini</span>
        <span style="font-size:12px;color:var(--text-muted);">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Ruangan</th>
                    <th>Waktu</th>
                    <th>Keperluan</th>
                    <th>Status Kunci</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamansHariIni as $i => $p)
                @php
                    $sudahAmbil   = !is_null($p->waktu_kunci_diambil);
                    $sudahKembali = !is_null($p->waktu_kunci_dikembalikan);
                    $rowClass     = $sudahKembali ? 'row-done' : (!$sudahAmbil ? 'row-waiting' : '');
                @endphp
                <tr class="{{ $rowClass }}">
                    <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->user->name ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">
                            {{ $p->user->organisasi ?? '' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">
                            {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;">
                        <span style="font-weight:600;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}</span>
                        –
                        <span>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}</span>
                    </td>
                    <td style="font-size:12.5px;max-width:160px;">
                        <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $p->keperluan }}">
                            {{ $p->keperluan }}
                        </div>
                    </td>
                    <td>
                        <div class="kunci-flow">
                            <span class="kunci-step {{ $sudahAmbil ? 'kunci-step-done' : 'kunci-step-pending' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sudahAmbil)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                Ambil
                            </span>
                            <span class="kunci-arrow">›</span>
                            <span class="kunci-step {{ $sudahKembali ? 'kunci-step-done' : 'kunci-step-pending' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sudahKembali)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @endif
                                </svg>
                                Kembali
                            </span>
                        </div>
                        @if($sudahAmbil && !$sudahKembali)
                        <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">
                            Diambil {{ \Carbon\Carbon::parse($p->waktu_kunci_diambil)->format('H:i') }}
                        </div>
                        @endif
                        @if($sudahKembali)
                        <div style="font-size:11px;color:#15803d;margin-top:3px;">
                            Kembali {{ \Carbon\Carbon::parse($p->waktu_kunci_dikembalikan)->format('H:i') }}
                        </div>
                        @endif
                    </td>
                    <td>
                        @if(!$sudahAmbil)
                            <form method="POST" action="{{ route('pamdal.kunci.ambil', $p->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm"
                                    onclick="return confirm('Konfirmasi kunci diambil oleh {{ $p->user->name ?? '' }}?')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    Ambil Kunci
                                </button>
                            </form>
                        @elseif(!$sudahKembali)
                            <form method="POST" action="{{ route('pamdal.kunci.kembalikan', $p->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Konfirmasi kunci dikembalikan oleh {{ $p->user->name ?? '' }}?')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Kembali
                                </button>
                            </form>
                        @else
                            <span class="badge badge-green">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Selesai
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p>Tidak ada peminjaman yang dijadwalkan hari ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection