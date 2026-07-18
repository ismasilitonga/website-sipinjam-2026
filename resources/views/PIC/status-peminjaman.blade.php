@extends('layouts.pic')

@section('title', 'Status Peminjaman')
@section('subtitle', 'Daftar peminjaman yang menunggu validasi atau ditolak')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom: 16px;">
        <span class="card-title">Semua Peminjaman</span>
        <span class="badge badge-gray">{{ $peminjaman_ruangans->total() }} total</span>
    </div>

    <div class="table-wrap" style="overflow-x: auto;">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Ormawa</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Alasan Tolak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman_ruangans as $p)
                    @php
                        $tglMulai   = \Carbon\Carbon::parse($p->tanggal_mulai)->locale('id');
                        $tglSelesai = \Carbon\Carbon::parse($p->tanggal_selesai)->locale('id');
                        $satuHari   = $tglMulai->isSameDay($tglSelesai);
                    @endphp
                    <tr>
                        <td style="color: var(--text-muted); font-size: 12px;">
                            {{ ($peminjaman_ruangans->currentPage() - 1) * $peminjaman_ruangans->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div style="font-weight: 500; font-size: 13px; white-space: nowrap;">{{ $p->user->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                        </td>
                        <td style="font-size: 12.5px;">{{ $p->nama_ormawa ?? '-' }}</td>
                        <td style="font-size: 13px; font-weight: 500; white-space: nowrap;">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                        <td style="font-size: 12.5px; white-space: nowrap;">
                            @if($satuHari)
                                {{ $tglMulai->translatedFormat('d F Y') }}
                            @else
                                <div>{{ $tglMulai->translatedFormat('d F Y') }}</div>
                                <div>s/d {{ $tglSelesai->translatedFormat('d F Y') }}</div>
                            @endif
                        </td>
                        <td>
                            @php
                                [$cls, $lbl] = match($p->status) {
                                    'menunggu_ketua' => ['badge-yellow', 'Menunggu Ketua'],
                                    'menunggu_pic'   => ['badge-purple', 'Menunggu PIC'],
                                    'ditolak'      => ['badge-red',    'Ditolak'],
                                    default        => ['badge-gray',   ucfirst($p->status)],
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ $lbl }}</span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted); max-width: 160px;">
                            @if($p->alasan_tolak)
                                <span title="{{ $p->alasan_tolak }}" style="cursor: help; border-bottom: 1px dashed #cbd5e1;">
                                    {{ Str::limit($p->alasan_tolak, 40) }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pic.status-peminjaman.detail', $p->id) }}" class="btn btn-outline" style="font-size: 12px; padding: 4px 12px;">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p>Tidak ada peminjaman yang perlu ditinjau.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjaman_ruangans->hasPages())
        <div class="pagination-wrap">{{ $peminjaman_ruangans->links('layouts.pagination') }}</div>
    @endif
</div>

@endsection