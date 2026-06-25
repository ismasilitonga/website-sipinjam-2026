@extends('layouts.admin')

@section('title', 'Status Peminjaman')
@section('subtitle', 'Daftar peminjaman yang menunggu validasi atau ditolak')

@section('topbar-action')
    <a href="{{ route('admin.laporan.unduh') }}" class="btn btn-outline">
        Unduh Laporan
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Peminjaman</span>
        <span class="badge badge-gray">{{ $peminjaman_ruangans->total() }} total</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th style="width: 120px;">Peminjam</th>
            <th style="width: 80px;">Organisasi</th>
            <th style="width: 130px;">Ruangan</th>
            <th style="width: 150px;">Tanggal & Waktu</th>
            <th style="width: 80px;">Status</th>
            <th style="width: 70px;">Aksi</th>
        </tr>
        </thead>
            <tbody>
                @forelse($peminjaman_ruangans as $p)
                <tr>
                    <td>{{ ($peminjaman_ruangans->currentPage() - 1) * $peminjaman_ruangans->perPage() + $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $p->user->nama ?? '-' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                    </td>
                    <td>{{ $p->user->organisasi ?? '-' }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $p->ruangan->kode ?? '' }}</div>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }},
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                    </td>
                    <td>
                        @php
                            $statusLabel = match($p->status) {
                                'ditolak'        => 'Ditolak',
                                'menunggu_pic',
                                'menunggu_ketua' => 'Menunggu',
                                default          => 'Menunggu',
                            };
                            $badgeClass = match($p->status) {
                                'ditolak' => 'badge-red',
                                default   => 'badge-orange',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.status-peminjaman.detail', $p->id) }}"
                           class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Tidak ada peminjaman yang perlu ditinjau.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjaman_ruangans->hasPages())
    <div class="pagination-wrap">
        {{ $peminjaman_ruangans->links('layouts.pagination') }}
    </div>
    @endif
</div>
@endsection