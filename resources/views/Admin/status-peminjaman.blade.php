@extends('layouts.admin')

@section('title', 'Status Peminjaman')
@section('subtitle', 'Pantau semua aktivitas peminjaman ruangan')

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
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Organisasi</th>
                    <th>Ruangan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman_ruangans as $p)
                <tr>
                    <td>{{ ($peminjaman_ruangans->currentPage() - 1) * $peminjaman_ruangans->perPage() + $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $p->user->name ?? '-' }}</div>
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
                                'disetujui'      => 'Disetujui',
                                'ditolak'        => 'Ditolak',
                                'berjalan'       => 'Berjalan',
                                'selesai'        => 'Selesai',
                                'menunggu_pic',
                                'menunggu_ketua' => 'Menunggu',
                                default          => 'Menunggu',
                            };
                            $badgeClass = match($p->status) {
                                'disetujui' => 'badge-green',
                                'ditolak'   => 'badge-red',
                                'berjalan'  => 'badge-blue',
                                'selesai'   => 'badge-gray',
                                default     => 'badge-orange',
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
                    <td colspan="7" style="text-align:center;">Tidak ada data peminjaman.</td>
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