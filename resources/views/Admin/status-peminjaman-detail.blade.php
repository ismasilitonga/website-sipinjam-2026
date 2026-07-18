@extends('layouts.admin')

@section('title', 'Detail Peminjaman')
@section('subtitle', 'Informasi lengkap peminjaman ruangan')

@section('content')
<div class="card" style="max-width:860px;">
    <div class="card-header" style="padding: 20px 24px 16px;">
        <span class="card-title">Detail Peminjaman #{{ $peminjaman->id }}</span>
        @php
            $statusLabel = match($peminjaman->status) {
                'disetujui'      => 'Disetujui',
                'ditolak'        => 'Ditolak',
                'berjalan'       => 'Berjalan',
                'selesai'        => 'Selesai',
                'menunggu_pic',
                'menunggu_ketua' => 'Menunggu',
                default          => 'Menunggu',
            };
            $badgeClass = match($peminjaman->status) {
                'disetujui' => 'badge-green',
                'ditolak'   => 'badge-red',
                'berjalan'  => 'badge-blue',
                'selesai'   => 'badge-gray',
                default     => 'badge-orange',
            };
        @endphp
        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
    </div>

    <div style="padding: 0 24px;">

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Peminjam</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;">{{ $peminjaman->user->nama ?? '-' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ $peminjaman->user->nim ?? '' }}</div>
                <div style="font-size: 12px; color: var(--text-muted);">{{ $peminjaman->nama_ormawa }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Ruangan</div>
            <div>
                <div style="font-size: 14px; font-weight: 600;">{{ $peminjaman->ruangan->nama_ruangan ?? '-' }}</div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ $peminjaman->ruangan->kode ?? '' }}</div>
            </div>
        </div>

        @php
            $tglMulai   = \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->locale('id');
            $tglSelesai = \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->locale('id');
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
            <div style="font-size: 14px;">{{ $peminjaman->keperluan }}</div>
        </div>

        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Dokumen Pendukung</div>
            <div style="font-size: 14px;">
                @if($peminjaman->dokumen_pendukung)
                    <a href="{{ Storage::url($peminjaman->dokumen_pendukung) }}" target="_blank"
                       style="color: var(--accent); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
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

        @if($peminjaman->status !== 'ditolak')
        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Status Kunci</div>
            <div>
                @php
                    if ($peminjaman->waktu_kunci_dikembalikan) {
                        $pemakaianLabel = 'Selesai';
                        $pemakaianClass = 'badge-gray';
                    } elseif ($peminjaman->waktu_kunci_diambil) {
                        $pemakaianLabel = 'Sedang Berjalan';
                        $pemakaianClass = 'badge-blue';
                    } elseif (in_array($peminjaman->status, ['disetujui', 'berjalan'])) {
                        $pemakaianLabel = 'Kunci Belum Diambil';
                        $pemakaianClass = 'badge-orange';
                    } else {
                        $pemakaianLabel = 'Belum Tersedia';
                        $pemakaianClass = 'badge-gray';
                    }
                @endphp
                <span class="badge {{ $pemakaianClass }}">{{ $pemakaianLabel }}</span>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">
                    Kunci diambil: {{ $peminjaman->waktu_kunci_diambil ? \Carbon\Carbon::parse($peminjaman->waktu_kunci_diambil)->locale('id')->translatedFormat('d F Y, H:i') : '-' }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted);">
                    Kunci dikembalikan: {{ $peminjaman->waktu_kunci_dikembalikan ? \Carbon\Carbon::parse($peminjaman->waktu_kunci_dikembalikan)->locale('id')->translatedFormat('d F Y, H:i') : '-' }}
                </div>
            </div>
        </div>
        @endif

        @if($peminjaman->checkIn)
        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border);">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Foto Data Diri Check-in</div>
            <div>
                <img src="{{ Storage::url($peminjaman->checkIn->foto_ktp) }}" alt="Foto KTP"
                     style="max-width: 260px; max-height: 160px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); display: block; margin-bottom: 6px;">
                <div style="font-size: 12px; color: var(--text-muted);">
                    Check-in pada {{ \Carbon\Carbon::parse($peminjaman->checkIn->waktu_checkin)->locale('id')->translatedFormat('d F Y, H:i') }}
                </div>
            </div>
        </div>
        @endif

        @if($peminjaman->status === 'ditolak' && $peminjaman->alasan_tolak)
        <div style="display: grid; grid-template-columns: 160px 1fr; gap: 16px; padding: 16px 0;">
            <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; padding-top: 3px;">Alasan Ditolak</div>
            <div>
                <div style="font-size: 14px; color: #ef4444;">{{ $peminjaman->alasan_tolak }}</div>
                @if($peminjaman->ditolak_oleh)
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                        Ditolak oleh: <strong>{{ match($peminjaman->ditolak_oleh) {
                            'ketua'  => 'Ketua Ormawa',
                            'pic'    => 'PIC',
                            'sistem' => 'Sistem (otomatis)',
                            default  => ucfirst($peminjaman->ditolak_oleh),
                        } }}</strong>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div style="margin-top: 16px;">
    <a href="{{ route('admin.status-peminjaman') }}" class="btn btn-primary" style="font-size: 13px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Status
    </a>
</div>
@endsection