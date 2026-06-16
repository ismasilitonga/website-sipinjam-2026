@extends('layouts.anggota')

@section('title', 'Serah Terima Barang')
@section('subtitle', 'Informasi jadwal serah terima barang dengan PIC')

@section('content')

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    <div class="card">
        <div class="card-header">
            <span class="card-title">Panduan Serah Terima Barang</span>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0;">
                @php
                $steps = [
                    [
                        'num'   => '1',
                        'icon'  => '📋',
                        'title' => 'Pengajuan Disetujui',
                        'desc'  => 'Setelah pengajuan barang kamu disetujui oleh PIC, kamu akan dihubungi untuk jadwal serah terima.',
                        'color' => '#ede9fe',
                        'tcolor'=> '#7c3aed',
                    ],
                    [
                        'num'   => '2',
                        'icon'  => '📍',
                        'title' => 'Ambil Barang dari PIC',
                        'desc'  => 'Datangi PIC sesuai jadwal yang disepakati. PIC akan menyerahkan barang dan mencatat waktu serah terima.',
                        'color' => '#dbeafe',
                        'tcolor'=> '#1d4ed8',
                    ],
                    [
                        'num'   => '3',
                        'icon'  => '✅',
                        'title' => 'Gunakan Barang',
                        'desc'  => 'Gunakan barang sesuai keperluan yang tercantum pada pengajuan. Jaga kondisi barang dengan baik.',
                        'color' => '#dcfce7',
                        'tcolor'=> '#15803d',
                    ],
                    [
                        'num'   => '4',
                        'icon'  => '🔄',
                        'title' => 'Kembalikan Tepat Waktu',
                        'desc'  => 'Kembalikan barang kepada PIC sesuai tanggal rencana pengembalian. PIC akan mencatat penerimaan kembali.',
                        'color' => '#ffedd5',
                        'tcolor'=> '#c2410c',
                    ],
                    [
                        'num'   => '5',
                        'icon'  => '🔁',
                        'title' => 'Pengalihan Barang (Opsional)',
                        'desc'  => 'Jika barang perlu dialihkan ke anggota lain, gunakan fitur Pengalihan Barang. Penerima harus mengkonfirmasi.',
                        'color' => '#f0fdf4',
                        'tcolor'=> '#16a34a',
                    ],
                ];
                @endphp

                @foreach($steps as $s)
                <div style="display:flex;gap:16px;padding:18px 0;
                            {{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                    <div style="width:42px;height:42px;border-radius:12px;flex-shrink:0;
                                background:{{ $s['color'] }};
                                display:flex;align-items:center;justify-content:center;font-size:20px;">
                        {{ $s['icon'] }}
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;margin-bottom:4px;color:{{ $s['tcolor'] }};">
                            Tahap {{ $s['num'] }}: {{ $s['title'] }}
                        </div>
                        <div style="font-size:13px;color:var(--text-muted);line-height:1.6;">
                            {{ $s['desc'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Aksi --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Menu Terkait</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:9px;">
                <a href="{{ route('anggota.pengajuan-barang') }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Peminjaman Barang
                </a>
                <a href="{{ route('anggota.riwayat-barang') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ValidasiPeminjaman Barang
                </a>
                <a href="{{ route('anggota.pengalihan-barang') }}" class="btn btn-outline">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    Pengalihan Barang
                </a>
            </div>
        </div>

        <div class="card" style="border-color:#ddd6fe;">
            <div class="card-body">
                <div style="font-size:13px;font-weight:600;color:var(--accent);margin-bottom:8px;">
                    💡 Tips Serah Terima
                </div>
                <ul style="font-size:12.5px;color:var(--text-muted);list-style:none;display:flex;flex-direction:column;gap:7px;">
                    <li>• Periksa kondisi barang saat menerima dan mengembalikan.</li>
                    <li>• Laporkan kerusakan segera melalui fitur <strong>Lapor Insiden</strong>.</li>
                    <li>• Pastikan jumlah barang yang dikembalikan sesuai dengan yang diterima.</li>
                    <li>• Hubungi PIC jika ada kendala dalam proses serah terima.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
