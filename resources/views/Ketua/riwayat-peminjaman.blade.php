@extends('layouts.ketua')

@section('title', 'Riwayat Peminjaman Ruangan')
@section('subtitle', 'Seluruh riwayat peminjaman ruangan oleh ormawa')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Riwayat Peminjaman</span>
        <span class="badge badge-gray">{{ $riwayat->total() }} data</span>
    </div>

    <div style="padding: 0 20px 20px; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <span style="font-size:14px; color:var(--text-muted); margin-right:4px;">Filter:</span>
        @php
            $filterStatus = request('status', '');
            $filters = [
                ''               => 'Semua',
                'menunggu_ketua' => 'Menunggu Ketua',
                'menunggu_pic'   => 'Menunggu PIC',
                'disetujui'      => 'Disetujui',
                'ditolak'        => 'Ditolak',
                'berjalan'       => 'Berjalan',
                'selesai'        => 'Selesai',
            ];
        @endphp
        @foreach($filters as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => 1]) }}"
               class="badge {{ $filterStatus === (string)$val ? 'badge-purple' : 'badge-gray' }}"
               style="text-decoration:none; padding:6px 14px; font-size:12px; border-radius:999px;">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Ormawa</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $p)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->user->name ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                    </td>
                    <td style="font-size:12.5px;">{{ $p->nama_ormawa ?? '-' }}</td>
                    <td style="font-size:13px;font-weight:500;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                    <td style="font-size:12.5px;white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }},
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                    </td>
                    <td style="font-size:12.5px;max-width:160px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $p->keperluan }}">
                            {{ $p->keperluan }}
                        </div>
                    </td>
                    <td>
                        @php
                            [$cls, $lbl] = match($p->status) {
                                'menunggu_ketua' => ['badge-yellow', 'Menunggu Ketua'],
                                'menunggu_pic'   => ['badge-purple', 'Menunggu PIC'],
                                'disetujui'      => ['badge-green',  'Disetujui'],
                                'ditolak'        => ['badge-red',    'Ditolak'],
                                'berjalan'       => ['badge-blue',   'Berjalan'],
                                'selesai'        => ['badge-gray',   'Selesai'],
                                default          => ['badge-gray',   ucfirst($p->status)],
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lbl }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Belum ada riwayat peminjaman.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($riwayat->hasPages())
    <div class="pagination-wrap">{{ $riwayat->links() }}</div>
    @endif
</div>

@endsection