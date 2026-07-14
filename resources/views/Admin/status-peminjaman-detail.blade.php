@extends('layouts.admin')

@section('title', 'Detail Peminjaman')
@section('subtitle', 'Informasi lengkap peminjaman ruangan')

@section('topbar-action')
    <a href="{{ route('admin.status-peminjaman') }}" class="btn btn-outline">← Kembali</a>
@endsection

@section('content')
<div class="card" style="max-width:720px;">
    <div class="card-header">
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
    <div style="padding:24px;display:flex;flex-direction:column;gap:20px;">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Peminjam</div>
            <div style="font-weight:600;font-size:15px;">{{ $peminjaman->user->nama ?? '-' }}</div>
            <div style="font-size:13px;color:var(--text-muted);">{{ $peminjaman->user->nim ?? '' }}</div>
            <div style="font-size:13px;color:var(--text-muted);">{{ $peminjaman->nama_ormawa }}</div>
        </div>
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Ruangan</div>
            <div style="font-weight:600;font-size:15px;">{{ $peminjaman->ruangan->nama_ruangan ?? '-' }}</div>
            <div style="font-size:13px;color:var(--text-muted);">{{ $peminjaman->ruangan->kode ?? '' }}</div>
        </div>
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Waktu Peminjaman</div>
            <div style="font-size:14px;">
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('d M Y, H:i') }}
                –
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('H:i') }}
            </div>
        </div>
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Keperluan</div>
            <div style="font-size:14px;">{{ $peminjaman->keperluan }}</div>
        </div>
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Dokumen Pendukung</div>
            <div style="font-size:14px;">
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
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Status Kunci</div>
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
            <div style="font-size:13px;color:var(--text-muted);margin-top:8px;">
                Kunci diambil: {{ $peminjaman->waktu_kunci_diambil ? \Carbon\Carbon::parse($peminjaman->waktu_kunci_diambil)->format('d M Y, H:i') : '-' }}
            </div>
            <div style="font-size:13px;color:var(--text-muted);">
                Kunci dikembalikan: {{ $peminjaman->waktu_kunci_dikembalikan ? \Carbon\Carbon::parse($peminjaman->waktu_kunci_dikembalikan)->format('d M Y, H:i') : '-' }}
            </div>
        </div>
        @if($peminjaman->checkIn)
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Foto Data Diri Check-in</div>
            <img src="{{ Storage::url($peminjaman->checkIn->foto_ktp) }}" alt="Foto KTP"
                 style="max-width:260px;max-height:160px;object-fit:cover;border-radius:8px;border:1px solid var(--border);display:block;margin-bottom:6px;">
            <div style="font-size:13px;color:var(--text-muted);">
                Check-in pada {{ \Carbon\Carbon::parse($peminjaman->checkIn->waktu_checkin)->format('d M Y, H:i') }}
            </div>
        </div>
        @endif
        @if($peminjaman->status === 'ditolak' && $peminjaman->alasan_tolak)
        <hr style="border:none;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:8px;">Alasan Penolakan</div>
            <div style="font-size:14px;color:var(--danger);">{{ $peminjaman->alasan_tolak }}</div>
            @if($peminjaman->ditolak_oleh)
                <div style="font-size:13px;color:var(--text-muted);margin-top:6px;">
                    Ditolak oleh: <strong>{{ match($peminjaman->ditolak_oleh) {
                        'ketua'  => 'Ketua Ormawa',
                        'pic'    => 'PIC',
                        'sistem' => 'Sistem (otomatis)',
                        default  => ucfirst($peminjaman->ditolak_oleh),
                    } }}</strong>
                </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection