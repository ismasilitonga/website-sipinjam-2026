@extends('layouts.pic')

@section('title', 'Detail Peminjaman Barang')
@section('subtitle', 'Informasi lengkap pengajuan peminjaman barang')

@section('content')

<div style="max-width: 680px;">
    <div class="card">
        <div class="card-header" style="padding-bottom: 16px;">
            <span class="card-title">Detail Peminjaman Barang</span>
            @php
                [$cls, $lbl] = match($peminjaman->status) {
                    'menunggu_ketua' => ['badge-yellow', 'Menunggu Ketua'],
                    'menunggu_pic'   => ['badge-purple', 'Menunggu PIC'],
                    'disetujui'      => ['badge-green',  'Disetujui'],
                    'ditolak'        => ['badge-red',    'Ditolak'],
                    default          => ['badge-gray',   ucfirst($peminjaman->status)],
                };
            @endphp
            <span class="badge {{ $cls }}">{{ $lbl }}</span>
        </div>

        <div style="padding: 0 20px 20px;">

            @php
                $rows = [
                    ['Peminjam',        $peminjaman->user->name ?? '-'],
                    ['NIM',             $peminjaman->user->nim  ?? '-'],
                    ['Ormawa',          $peminjaman->nama_ormawa ?? '-'],
                    ['Barang',          ($peminjaman->barang->nama ?? '-') . ' · ' . ($peminjaman->barang->kode ?? '')],
                    ['Jumlah',          $peminjaman->jumlah . ' ' . ($peminjaman->barang->satuan ?? 'unit')],
                    ['Tanggal Pinjam',  \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y')],
                    ['Rencana Kembali', \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y')],
                    ['Keperluan',       $peminjaman->keperluan ?? '-'],
                ];
            @endphp

            @foreach($rows as [$label, $value])
                <div style="display: flex; padding: 13px 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 160px; flex-shrink: 0; font-size: 12px; font-weight: 600;
                                color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px;
                                padding-top: 1px;">
                        {{ $label }}
                    </div>
                    <div style="font-size: 13.5px; color: var(--text-primary); flex: 1;">
                        {{ $value }}
                    </div>
                </div>
            @endforeach

            @if($peminjaman->status === 'ditolak' && $peminjaman->alasan_tolak)
                <div style="display: flex; padding: 13px 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 160px; flex-shrink: 0; font-size: 12px; font-weight: 600;
                                color: #dc2626; text-transform: uppercase; letter-spacing: 0.4px; padding-top: 1px;">
                        Alasan Ditolak
                    </div>
                    <div style="font-size: 13.5px; color: #dc2626; flex: 1;">
                        {{ $peminjaman->alasan_tolak }}
                    </div>
                </div>
            @endif

            @if($peminjaman->waktu_diserahkan)
                <div style="display: flex; padding: 13px 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 160px; flex-shrink: 0; font-size: 12px; font-weight: 600;
                    color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; padding-top: 1px;">
                        Diserahkan Pada
                    </div>
                    <div style="font-size: 13.5px; color: var(--text-primary); flex: 1;">
                        {{ \Carbon\Carbon::parse($peminjaman->waktu_diserahkan)->format('d M Y, H:i') }}
                    </div>
                </div>
            @endif

            @if($peminjaman->waktu_diterima_kembali)
                <div style="display: flex; padding: 13px 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 160px; flex-shrink: 0; font-size: 12px; font-weight: 600;
                                color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; padding-top: 1px;">
                        Dikembalikan Pada
                    </div>
                    <div style="font-size: 13.5px; color: var(--text-primary); flex: 1;">
                        {{ \Carbon\Carbon::parse($peminjaman->waktu_diterima_kembali)->format('d M Y, H:i') }}
                    </div>
                </div>
            @endif

            @if($peminjaman->foto_serah)
                <div style="display: flex; padding: 13px 0; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 160px; flex-shrink: 0; font-size: 12px; font-weight: 600;
                        color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; padding-top: 1px;">
                        Foto Serah
                    </div>
                    <div>
                        <a href="{{ Storage::url($peminjaman->foto_serah) }}" target="_blank">
                        <img src="{{ Storage::url($peminjaman->foto_serah) }}"
                        style="width: 120px; height: 120px; object-fit: cover;
                        border-radius: 8px; border: 1px solid #e5e7eb;">
                        </a>
                    </div>
                </div>
            @endif

            @if($peminjaman->foto_kembali)
                <div style="display: flex; padding: 13px 0;">
                <div style="width: 160px; flex-shrink: 0; font-size: 12px; font-weight: 600;
                    color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.4px; padding-top: 1px;">
                        Foto Kembali
                    </div>
                    <div>
                        <a href="{{ Storage::url($peminjaman->foto_kembali) }}" target="_blank">
                        <img src="{{ Storage::url($peminjaman->foto_kembali) }}"
                        style="width: 120px; height: 120px; object-fit: cover;
                        border-radius: 8px; border: 1px solid #e5e7eb;">
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div style="margin-top: 16px;">
        <a href="{{ route('pic.riwayat.barang') }}" class="btn btn-primary" style="font-size: 13px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 15px; height: 15px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Validasi Peminjaman
        </a>
    </div>
</div>

@endsection