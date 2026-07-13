@extends('layouts.ketua')

@section('title', 'Riwayat Peminjaman Ruangan')
@section('subtitle', 'Seluruh riwayat peminjaman ruangan dari ormawa')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom: 12px;">
        <div>
            <span class="card-title">Riwayat Peminjaman</span>
        </div>
        <span class="badge badge-gray">{{ $riwayat->total() }} data</span>
    </div>

    <div style="padding: 0 20px 16px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
        <span style="font-size: 12.5px; color: var(--color-text-secondary); font-weight: 500; margin-right: 4px;">Filter:</span>
        @foreach($filters as $value => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $value, 'page' => null]) }}"
               style="padding: 6px 14px; border-radius: 20px; font-size: 12.5px; font-weight: 500;
                      text-decoration: none; border: 1px solid;
                      {{ request('status', '') === $value
                          ? 'background: #16a34a; color: #fff; border-color: #16a34a;'
                          : 'background: transparent; color: var(--color-text-secondary); border-color: var(--color-border-tertiary);' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="table-wrap" style="overflow-x: auto;">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Peminjam</th>
                    <th>Ormawa</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Alasan Ditolak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $p)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 12px;">
                            {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div style="font-weight: 500; font-size: 13px; white-space: nowrap;">{{ $p->user->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                        </td>
                        <td style="font-size: 12.5px;">{{ $p->nama_ormawa ?? '-' }}</td>
                        <td style="font-size: 13px; font-weight: 500; white-space: nowrap;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                        <td style="font-size: 12px; white-space: nowrap;">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->translatedFormat('d M Y, H:i') }}–{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                        </td>
                        <td style="font-size: 12.5px; max-width: 160px;">
                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $p->keperluan ?? '-' }}
                            </div>
                        </td>
                        <td>
                            @php
                                [$cls, $lbl] = match($p->status) {
                                    'menunggu_ketua' => ['badge-yellow',  'Menunggu Ketua'],
                                    'menunggu_pic'   => ['badge-purple',  'Menunggu PIC'],
                                    'disetujui'      => ['badge-green',   'Disetujui'],
                                    'ditolak'        => ['badge-red',     'Ditolak'],
                                    'berjalan'       => ['badge-blue',    'Berjalan'],
                                    'selesai'        => ['badge-gray',    'Selesai'],
                                    default          => ['badge-gray',    ucfirst($p->status)],
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ $lbl }}</span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted); max-width: 160px;">
                            @if($p->status === 'ditolak' && $p->alasan_tolak)
                                <span title="{{ $p->alasan_tolak }}" style="cursor: help; border-bottom: 1px dashed #cbd5e1;">
                                    {{ Str::limit($p->alasan_tolak, 40) }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>Tidak ada riwayat peminjaman.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayat->hasPages())
        <div class="pagination-wrap">{{ $riwayat->links('layouts.pagination') }}</div>
    @endif
</div>

@endsection