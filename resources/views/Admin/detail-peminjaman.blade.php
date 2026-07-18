@extends('layouts.admin')

@section('title', 'Detail Peminjaman')
@section('subtitle', 'Informasi lengkap pengajuan peminjaman ruangan')

@section('content')

<div class="card" style="max-width: 860px;">

    <div class="card-header" style="padding: 20px 24px 16px;">
        <span class="card-title">Detail Peminjaman</span>
        @php
            [$cls, $lbl] = match($p->status) {
                'menunggu_ketua' => ['badge-yellow', 'Menunggu Ketua'],
                'menunggu_pic'   => ['badge-purple', 'Menunggu PIC'],
                'disetujui'      => ['badge-green',  'Disetujui'],
                'ditolak'        => ['badge-red',    'Ditolak'],
                'selesai'        => ['badge-gray',   'Selesai'],
                default          => ['badge-gray',   ucfirst($p->status)],
            };
        @endphp
        <span class="badge {{ $cls }}">{{ $lbl }}</span>
    </div>

    <div style="padding: 0 24px;">

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Peminjam</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;">{{ $p->user->nama ?? '-' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">NIM: {{ $p->user->nim ?? '-' }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Ormawa</div>
            <div style="font-size: 14px;">{{ $p->nama_ormawa ?? '-' }}</div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Ruangan</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                @if($p->ruangan)
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                        Lantai {{ $p->ruangan->lantai }}{{ $p->ruangan->gedung ? ' — ' . $p->ruangan->gedung : '' }}
                    </div>
                @endif
            </div>
        </div>

        @php
            $tglMulai   = \Carbon\Carbon::parse($p->tanggal_mulai)->locale('id');
            $tglSelesai = \Carbon\Carbon::parse($p->tanggal_selesai)->locale('id');
            $satuHari   = $tglMulai->isSameDay($tglSelesai);
        @endphp

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Tanggal</div>
            <div style="font-size: 14px;">
                @if($satuHari)
                    {{ $tglMulai->translatedFormat('l, d F Y') }}
                @else
                    {{ $tglMulai->translatedFormat('l, d F Y') }} s/d {{ $tglSelesai->translatedFormat('l, d F Y') }}
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Waktu</div>
            <div style="font-size: 14px;">
                @if($satuHari)
                    {{ $tglMulai->format('H:i') }} – {{ $tglSelesai->format('H:i') }}
                @else
                    Setiap {{ $tglMulai->format('H:i') }} – {{ $tglSelesai->format('H:i') }}
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Keperluan</div>
            <div style="font-size: 14px;">{{ $p->keperluan ?? '-' }}</div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; {{ $p->alasan_tolak ? 'border-bottom: 1px solid var(--border);' : '' }}">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Dokumen Pendukung</div>
            <div style="font-size: 14px;">
                @if($p->dokumen_pendukung)
                    <a href="{{ Storage::url($p->dokumen_pendukung) }}" target="_blank"
                       style="color: var(--accent); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Dokumen
                    </a>
                @else
                    <span style="color: var(--text-muted);">Tidak ada dokumen</span>
                @endif
            </div>
        </div>
        
        @if($p->checkIn && auth()->user()->role !== 'pic')
            <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Identitas Check-in</div>
                <div>
                    <img src="{{ route('foto-identitas.show', $p->checkIn->id) }}" alt="Foto Identitas"
                         style="max-width: 260px; max-height: 160px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); display: block; margin-bottom: 6px;">
                    <div style="font-size: 12px; color: var(--text-muted);">
                        Check-in pada {{ \Carbon\Carbon::parse($p->checkIn->waktu_checkin)->locale('id')->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
            </div>
        @endif

        @if($p->alasan_tolak)
            <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0;">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Alasan Ditolak</div>
                <div>
                    <div style="font-size: 14px; color: #ef4444;">{{ $p->alasan_tolak }}</div>
                    @if($p->ditolak_oleh)
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                            Ditolak oleh: <strong>{{ match($p->ditolak_oleh) {
                                'ketua'  => 'Ketua Ormawa',
                                'pic'    => 'PIC',
                                'sistem' => 'Sistem (otomatis)',
                                default  => ucfirst($p->ditolak_oleh),
                            } }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<div style="margin-top: 16px;">
    @if(request()->headers->get('referer') && str_contains(request()->headers->get('referer'), 'riwayat'))
        <a href="{{ auth()->user()->role === 'admin'
            ? route('admin.riwayat-peminjaman')
            : route('pic.riwayat-peminjaman') }}"
           class="btn btn-primary" style="font-size: 13px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Riwayat
        </a>
    @else
        <a href="{{ route('pic.status-peminjaman') }}" class="btn btn-primary" style="font-size: 13px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Status
        </a>
    @endif
</div>

@endsection