@extends('layouts.anggota')

@section('title', $ruangan->nama_ruangan)
@section('subtitle', 'Detail informasi ruangan')

@section('topbar-action')
    <div style="display:flex;gap:8px;">
        @if(($ruangan->status ?? 'tersedia') === 'tersedia')
        <a href="{{ route('anggota.pengajuan-ruangan') }}?ruangan_id={{ $ruangan->id }}"
           class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Sekarang
        </a>
        @endif
        <a href="{{ route('anggota.daftar-ruangan') }}" class="btn btn-outline">Kembali</a>
    </div>
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    <div class="card">
        <div style="width:100%;height:200px;background:linear-gradient(135deg,#eef2ff,#e0f2fe);
                    display:flex;align-items:center;justify-content:center;
                    border-radius:14px 14px 0 0;">
            <svg fill="none" stroke="#a5b4fc" viewBox="0 0 24 24" style="width:64px;height:64px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>

        <div style="padding:0;">
            <div class="detail-row">
                <div class="detail-label">Nama Ruangan</div>
                <div class="detail-value" style="font-weight:600;">{{ $ruangan->nama_ruangan }}</div>
            </div>
            @isset($ruangan->kode)
            <div class="detail-row">
                <div class="detail-label">Kode</div>
                <div class="detail-value"><span style="font-family:monospace;">{{ $ruangan->kode }}</span></div>
            </div>
            @endisset
            @isset($ruangan->gedung)
            <div class="detail-row">
                <div class="detail-label">Gedung</div>
                <div class="detail-value">{{ $ruangan->gedung }}</div>
            </div>
            @endisset
            @isset($ruangan->lantai)
            <div class="detail-row">
                <div class="detail-label">Lantai</div>
                <div class="detail-value">Lantai {{ $ruangan->lantai }}</div>
            </div>
            @endisset
            @isset($ruangan->kapasitas)
            <div class="detail-row">
                <div class="detail-label">Kapasitas</div>
                <div class="detail-value">{{ $ruangan->kapasitas }} orang</div>
            </div>
            @endisset
            @isset($ruangan->fasilitas)
            <div class="detail-row">
                <div class="detail-label">Fasilitas</div>
                <div class="detail-value">{{ $ruangan->fasilitas }}</div>
            </div>
            @endisset
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @php
                        $st = $ruangan->status ?? 'tersedia';
                        $stCls = $st === 'tersedia' ? 'badge-green' : ($st === 'dipinjam' ? 'badge-orange' : 'badge-red');
                    @endphp
                    <span class="badge {{ $stCls }}">{{ ucfirst($st) }}</span>
                </div>
            </div>
            @isset($ruangan->deskripsi)
            <div class="detail-row">
                <div class="detail-label">Deskripsi</div>
                <div class="detail-value">{{ $ruangan->deskripsi }}</div>
            </div>
            @endisset
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Ajukan Peminjaman</span></div>
        <div class="card-body">
            @if(($ruangan->status ?? 'tersedia') === 'tersedia')
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">
                Ruangan ini tersedia. Klik tombol di bawah untuk memulai pengajuan.
            </p>
            <a href="{{ route('anggota.pengajuan-ruangan') }}?ruangan_id={{ $ruangan->id }}"
               class="btn btn-primary" style="width:100%;justify-content:center;">
                Ajukan Ruangan Ini
            </a>
            @else
            <div class="alert alert-warning" style="margin-bottom:0;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Ruangan saat ini tidak tersedia untuk dipinjam.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
