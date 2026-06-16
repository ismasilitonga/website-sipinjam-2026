@php
    $layout = match(auth()->user()->role) {
        'ketua'   => 'layouts.ketua',
        'anggota' => 'layouts.anggota',
        'pamdal'  => 'layouts.pamdal',
        'pic'     => 'layouts.pic',
        'admin'   => 'layouts.admin',
    };
@endphp

@extends($layout)

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
        @php $filterStatus = request('status', ''); @endphp
        @foreach($filters as $val => $label)
            <a href="{{ $val === '' ? request()->url() . '?page=1' : request()->fullUrlWithQuery(['status' => $val, 'page' => 1]) }}"
               class="badge {{ $filterStatus === (string)$val ? 'badge-purple' : 'badge-gray' }}"
               style="text-decoration:none; padding:8px 20px; border-radius:999px; font-size:12px; font-weight:500;">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="table-wrap" style="overflow-x:auto;">
    <table style="width:100%;">
        <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:140px;">Peminjam</th>
                    <th style="width:100px;">Ormawa</th>
                    <th style="width:120px;">Ruangan</th>
                    <th style="width:160px;">Tanggal</th>
                    <th style="width:140px;">Keperluan</th>
                    <th style="width:140px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $p)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                    </td>
                    <td>
                    <div style="font-weight:500;font-size:13px;white-space:nowrap;">{{ $p->user->name ?? '-' }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                    </td>
                    <td style="font-size:12.5px;">{{ $p->nama_ormawa ?? '-' }}</td>
                    <td style="font-size:13px;font-weight:500;white-space:nowrap;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
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
    <div class="pagination-wrap">
        {{ $riwayat->links('layouts.pagination') }}
    </div>
    @endif
</div>

@endsection