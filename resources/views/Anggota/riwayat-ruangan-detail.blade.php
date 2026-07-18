@extends('layouts.anggota')

@section('title', 'Detail Pengajuan')
@section('subtitle', 'Detail lengkap pengajuan ruangan kamu')

@section('content')
@php
    $tglMulai   = \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->locale('id');
    $tglSelesai = \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->locale('id');
    $satuHari   = $tglMulai->isSameDay($tglSelesai);

    $labelDitolak = match($peminjaman->ditolak_oleh) {
        'ketua'  => 'Ditolak Ketua',
        'pic'    => 'Ditolak PIC',
        'sistem' => 'Ditolak Sistem',
        default  => 'Ditolak',
    };

    [$cls, $lbl] = match($peminjaman->status) {
        'menunggu_ketua' => ['badge-orange', 'Menunggu Ketua'],
        'menunggu_pic'   => ['badge-blue',   'Menunggu PIC'],
        'disetujui'      => ['badge-green',  'Disetujui'],
        'ditolak'        => ['badge-red',    $labelDitolak],
        'selesai'        => ['badge-gray',   'Selesai'],
        'berjalan'       => ['badge-cyan',   'Berjalan'],
        default          => ['badge-gray',   ucfirst($peminjaman->status)],
    };

    $ditolakOlehLabel = $peminjaman->ditolak_oleh ? match($peminjaman->ditolak_oleh) {
        'ketua'  => 'Ketua Ormawa',
        'pic'    => 'PIC',
        'sistem' => 'Sistem (otomatis)',
        default  => ucfirst($peminjaman->ditolak_oleh),
    } : null;
@endphp

<div class="card" style="max-width:640px;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <span class="card-title">Detail Pengajuan Ruangan</span>
        <span class="badge {{ $cls }}">{{ $lbl }}</span>
    </div>

    <div style="padding:20px;display:flex;flex-direction:column;gap:18px;">

        <div>
            <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:3px;">Ruangan</div>
            <div style="font-size:15px;font-weight:700;">{{ $peminjaman->ruangan->nama_ruangan ?? '-' }}</div>
            <div style="font-size:12px;color:var(--text-muted);">
                {{ $peminjaman->ruangan->gedung ?? '' }}{{ isset($peminjaman->ruangan->lantai) ? ' · Lt.'.$peminjaman->ruangan->lantai : '' }}
            </div>
        </div>

        <div style="display:flex;gap:24px;flex-wrap:wrap;">
            <div>
                <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:3px;">Tanggal</div>
                <div style="font-size:13.5px;">
                    @if($satuHari)
                        {{ $tglMulai->translatedFormat('d F Y') }}
                    @else
                        {{ $tglMulai->translatedFormat('d F Y') }} s/d {{ $tglSelesai->translatedFormat('d F Y') }}
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:3px;">Waktu</div>
                <div style="font-size:13.5px;">
                    {{ $tglMulai->format('H:i') }} – {{ $tglSelesai->format('H:i') }}
                    @unless($satuHari)
                        <span style="font-size:11.5px;color:var(--text-muted);">(setiap hari)</span>
                    @endunless
                </div>
            </div>
        </div>

        <div>
            <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:3px;">Keperluan</div>
            <div style="font-size:13.5px;white-space:normal;word-break:break-word;line-height:1.5;">
                {{ $peminjaman->keperluan }}
            </div>
        </div>

        <div>
            <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:3px;">Dokumen Pendukung</div>
            @if($peminjaman->dokumen_pendukung)
                <a href="{{ Storage::url($peminjaman->dokumen_pendukung) }}" target="_blank"
                   style="color:var(--accent);text-decoration:none;font-weight:600;font-size:13.5px;">
                    📄 Lihat Dokumen
                </a>
            @else
                <div style="font-size:13.5px;color:var(--text-muted);">—</div>
            @endif
        </div>

        @if($peminjaman->alasan_tolak)
        <div>
            <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:3px;">Alasan Tolak</div>
            <div style="font-size:13.5px;white-space:normal;word-break:break-word;line-height:1.5;">
                {{ $peminjaman->alasan_tolak }}
            </div>
            @if($ditolakOlehLabel)
                <div style="font-size:11px;color:#94a3b8;margin-top:3px;">oleh {{ $ditolakOlehLabel }}</div>
            @endif
        </div>
        @endif

        @if($peminjaman->status === 'disetujui' && ($peminjaman->status_pemakaian ?? '') === 'booked' && $tglMulai->isToday())
        <div>
            <a href="{{ route('anggota.checkin') }}"
               style="color:var(--accent);font-weight:600;font-size:13px;text-decoration:none;">
                → Check-in hari ini
            </a>
        </div>
        @endif

        <div style="border-top:1px solid #e5e7eb;padding-top:16px;">
        <a href="{{ route('anggota.riwayat-ruangan') }}" class="btn btn-primary"
        style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;text-decoration:none;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Riwayat
    </a>
</div>

    </div>
</div>
@endsection