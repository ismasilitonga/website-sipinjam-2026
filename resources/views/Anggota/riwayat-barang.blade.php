@extends('layouts.anggota')

@section('title', 'Validasi Peminjaman Barang')
@section('subtitle', 'Semua pengajuan barang yang pernah kamu ajukan')

@section('topbar-action')
    <a href="{{ route('anggota.pengajuan-barang') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:4px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajukan Baru
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Validasi Peminjaman Barang</span>
        <span class="badge badge-gray">{{ $riwayat->total() }} data</span>
    </div>

    <div style="padding: 0 20px 20px; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <span style="font-size:14px; color:var(--text-muted); margin-right:4px;">Filter:</span>
        @php $filterStatus = request('status', ''); @endphp
        
        @foreach($filters as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => 1]) }}"
               class="badge {{ $filterStatus === (string)$val ? 'badge-purple' : 'badge-gray' }}"
               style="text-decoration:none; padding:6px 14px; font-size: 12px; border-radius:999px;">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="table-wrap" style="overflow-x:auto;">
    <table style="width:100%;">
        <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:140px;">Barang</th>
                    <th style="width:100px;">Jumlah</th>
                    <th style="width:120px;">TGL Pinjam</th>
                    <th style="width:170px;">Rencana Kembali</th>
                    <th style="width:130px;">Keperluan</th>
                    <th style="width:140px;">Status</th>
                    <th style="width:140px;">Diserahkan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $pb)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:13px;white-space:nowrap;">{{ $pb->barang->nama ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);font-family:monospace;">
                            {{ $pb->barang->kode ?? '' }}
                        </div>
                    </td>
                    <td style="font-size:13px;">
                        <span style="font-weight:700;">{{ $pb->jumlah }}</span>
                        <span style="color:var(--text-muted);font-size:11.5px;"> {{ $pb->barang->satuan ?? '' }}</span>
                    </td>
                    <td style="font-size:12.5px;">
                        {{ \Carbon\Carbon::parse($pb->tanggal_pinjam)->format('d M Y') }}
                    </td>
                    <td style="font-size:12.5px;">
             @php
            $terlambat =
            $pb->status === 'disetujui'
            && !is_null($pb->waktu_diserahkan)
            && is_null($pb->waktu_diterima_kembali)
            && \Carbon\Carbon::today()->gt(
            \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)
            );
                @endphp
                        <span style="{{ $terlambat ? 'color:#dc2626;font-weight:700;' : '' }}">
                        {{ \Carbon\Carbon::parse($pb->tanggal_kembali_rencana)->format('d M Y') }}
                        </span>
                        @if($terlambat)
                            <span class="badge badge-red" style="margin-left:4px;font-size:10px;">Terlambat</span>
                        @endif
                    </td>
                    <td style="font-size:12.5px;max-width:160px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $pb->keperluan }}">
                            {{ $pb->keperluan }}
                        </div>
                    </td>
                    <td>
                        @php
                            [$cls, $lbl] = match($pb->status ?? 'menunggu_pic') {
                                'menunggu_pic'   => ['badge-blue',   'Menunggu PIC'],
                                'disetujui'      => ['badge-green',  'Disetujui'],
                                'ditolak'        => ['badge-red',    'Ditolak'],
                                'selesai'        => ['badge-gray',   'Selesai'],
                                default          => ['badge-gray',   ucfirst($pb->status ?? '-')],
                            };
                        @endphp
                       <span class="badge {{ $cls }}" style="white-space:nowrap;">{{ $lbl }}</span>
                    </td>
                    <td style="font-size:12px;">
                        @if($pb->waktu_diserahkan)
                            <span style="color:#16a34a;font-weight:600;">
                                ✓ {{ \Carbon\Carbon::parse($pb->waktu_diserahkan)->format('d M, H:i') }}
                            </span>
                            @if($pb->waktu_diterima_kembali)
                                <div style="font-size:11px;color:var(--text-muted);">
                                    Kembali: {{ \Carbon\Carbon::parse($pb->waktu_diterima_kembali)->format('d M') }}
                                </div>
                            @endif
                        @else
                            <span style="color:var(--text-muted);">Belum diserahkan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Belum ada riwayat peminjaman barang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riwayat->hasPages())
    <div class="pagination-wrap">
        {{ $riwayat->links('layouts.pagination') }}
    </div>
@endif
@endsection