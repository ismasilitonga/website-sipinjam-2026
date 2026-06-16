@extends('layouts.anggota')

@section('title', 'Riwayat Laporan Insiden')
@section('subtitle', 'Daftar insiden yang pernah kamu laporkan')

@section('topbar-action')
    <a href="{{ route('anggota.lapor-insiden') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        Lapor Insiden Baru
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Riwayat Laporan Insiden</span>
        <span class="badge badge-gray">{{ $insidens->total() }} laporan</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Lokasi</th>
                    <th>Tanggal Lapor</th>
                    <th>Status</th>
                    <th>Tindak Lanjut</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insidens as $ins)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($insidens->currentPage() - 1) * $insidens->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $ins->judul }}</div>
                        @if($ins->foto)
                            <a href="{{ asset('storage/'.$ins->foto) }}" target="_blank"
                               style="font-size:11.5px;color:var(--accent);text-decoration:none;">
                                📷 Lihat foto
                            </a>
                        @endif
                    </td>
                    <td style="font-size:12.5px;">{{ $ins->lokasi }}</td>
                    <td style="font-size:12.5px;">
                        {{ $ins->created_at->format('d M Y') }}<br>
                        <span style="color:var(--text-muted);">{{ $ins->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        @php
                            [$cls, $lbl] = match($ins->status) {
                                'dilaporkan'      => ['badge-orange', 'Dilaporkan'],
                                'ditindaklanjuti' => ['badge-blue',   'Diproses'],
                                'selesai'         => ['badge-green',  'Selesai'],
                                default           => ['badge-gray',   ucfirst($ins->status)],
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lbl }}</span>
                    </td>
                    <td style="font-size:12.5px;color:var(--text-muted);max-width:180px;">
                        @if($ins->tindak_lanjut)
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                 title="{{ $ins->tindak_lanjut }}">
                                {{ Str::limit($ins->tindak_lanjut, 50) }}
                            </div>
                        @else
                            <span style="color:#d4d4d8;">Belum ada</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('anggota.detail-laporan', $ins->id) }}"
                           class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p>Belum ada laporan insiden.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 @if($insidens->hasPages())
<div class="pagination-wrap">{{ $insidens->links() }}</div>
@endif
</div>
@endsection
