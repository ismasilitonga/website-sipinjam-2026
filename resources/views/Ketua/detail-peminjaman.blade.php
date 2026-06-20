@extends('layouts.ketua')

@section('title', 'Detail Peminjaman')
@section('subtitle', 'Informasi lengkap pengajuan peminjaman ruangan')

@section('content')

<div class="card" style="max-width: 860px;">

    <div class="card-header" style="padding: 20px 24px 16px;">
        <span class="card-title">Detail Peminjaman</span>
        @php
            [$cls, $lbl] = match($peminjaman->status) {
                'menunggu_ketua' => ['badge-yellow', 'Menunggu Ketua'],
                'menunggu_pic'   => ['badge-purple', 'Menunggu PIC'],
                'disetujui'      => ['badge-green',  'Disetujui'],
                'ditolak'        => ['badge-red',    'Ditolak'],
                'berjalan'       => ['badge-blue',   'Berjalan'],
                'selesai'        => ['badge-gray',   'Selesai'],
                default          => ['badge-gray',   ucfirst($peminjaman->status)],
            };
        @endphp
        <span class="badge {{ $cls }}">{{ $lbl }}</span>
    </div>

    <div style="padding: 0 24px;">

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Peminjam</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;">{{ $peminjaman->user->name ?? '-' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">NIM: {{ $peminjaman->user->nim ?? '-' }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Ormawa</div>
            <div style="font-size: 14px;">{{ $peminjaman->nama_ormawa ?? '-' }}</div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Ruangan</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;">{{ $peminjaman->ruangan->nama ?? '-' }}</div>
                @if($peminjaman->ruangan)
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                        Lantai {{ $peminjaman->ruangan->lantai }}{{ $peminjaman->ruangan->gedung ? ' – ' . $peminjaman->ruangan->gedung : '' }}
                    </div>
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Tanggal</div>
            <div style="font-size: 14px;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->translatedFormat('l, d F Y') }}</div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Waktu</div>
            <div style="font-size: 14px;">
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('H:i') }}
                –
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('H:i') }}
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; {{ $peminjaman->alasan_tolak ? 'border-bottom: 1px solid var(--border);' : '' }}">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Keperluan</div>
            <div style="font-size: 14px;">{{ $peminjaman->keperluan ?? '-' }}</div>
        </div>

        @if($peminjaman->alasan_tolak)
            <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Alasan Ditolak</div>
                <div style="font-size: 14px; color: #ef4444;">{{ $peminjaman->alasan_tolak }}</div>
            </div>
        @endif

    </div>
</div>

<div style="margin-top: 16px;">
    <a href="{{ route('ketua.riwayat-peminjaman') }}" class="btn btn-primary" style="font-size: 13px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Riwayat
    </a>
</div>

@endsection