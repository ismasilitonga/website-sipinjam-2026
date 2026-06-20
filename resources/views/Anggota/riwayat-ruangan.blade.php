@extends('layouts.anggota')

@section('title', 'Riwayat Pengajuan')
@section('subtitle', 'Semua pengajuan ruangan yang pernah kamu ajukan')

@section('topbar-action')
    <a href="{{ route('anggota.pengajuan-ruangan') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:4px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajukan Baru
    </a>
@endsection

@section('content')

<div id="modalBatal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;padding:28px;max-width:400px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <div style="text-align:center;margin-bottom:16px;">
            <div style="width:52px;height:52px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <svg fill="none" stroke="#ef4444" viewBox="0 0 24 24" style="width:26px;height:26px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div style="font-size:16px;font-weight:700;color:#111;">Batalkan Pengajuan?</div>
            <div style="font-size:13px;color:#6b7280;margin-top:6px;">Pengajuan ruangan ini akan dibatalkan dan tidak bisa dikembalikan.</div>
        </div>
<div style="display:flex;gap:10px;justify-content:center;align-items:center;margin-top:8px;">
<button onclick="tutupModal()" style="width:120px;height:40px;border:1.5px solid #d1d5db;border-radius:8px;background:#fff;font-size:14px;cursor:pointer;">Tidak</button>
    <button onclick="document.getElementById('formBatal').submit()"
        class="btn btn-primary"
        style="width:120px;height:40px;background:#ef4444;border-color:#ef4444;">
        Ya, Batalkan
    </button>
        </div>
        <form id="formBatal" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Riwayat Peminjaman Ruangan</span>
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

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Alasan Tolak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $p)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->ruangan->nama ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">
                            {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</td>
                    <td style="font-size:12.5px;white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                    </td>
                    <td style="font-size:12.5px;max-width:180px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $p->keperluan }}">
                            {{ $p->keperluan }}
                        </div>
                    </td>
                    <td>
                        @php
                            [$cls, $lbl] = match($p->status) {
                                'menunggu_ketua' => ['badge-orange', 'Menunggu Ketua'],
                                'menunggu_pic'   => ['badge-blue',   'Menunggu PIC'],
                                'disetujui'      => ['badge-green',  'Disetujui'],
                                'ditolak'        => ['badge-red',    'Ditolak'],
                                'selesai'        => ['badge-gray',   'Selesai'],
                                'berjalan'       => ['badge-cyan',   'Berjalan'],
                                default          => ['badge-gray',   ucfirst($p->status)],
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lbl }}</span>
                        @if($p->status === 'disetujui' && ($p->status_pemakaian ?? '') === 'booked' && \Carbon\Carbon::parse($p->tanggal_mulai)->isToday())
                            <a href="{{ route('anggota.checkin') }}"
                               style="display:block;margin-top:4px;font-size:11.5px;color:var(--accent);font-weight:600;text-decoration:none;">
                                → Check-in hari ini
                            </a>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);max-width:150px;">
                        @if($p->alasan_tolak)
                            <span title="{{ $p->alasan_tolak }}" style="cursor:help;border-bottom:1px dashed #cbd5e1;">
                                {{ Str::limit($p->alasan_tolak, 40) }}
                            </span>
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'menunggu_ketua')
                            <button onclick="bukaMModal('{{ route('anggota.pengajuan-ruangan.cancel', $p->id) }}')"
                                class="btn btn-outline"
                                style="font-size:12px;padding:4px 10px;color:var(--danger);border-color:var(--danger);">
                                Batalkan
                            </button>
                        @else —
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
                            <p>Belum ada riwayat peminjaman ruangan.</p>
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

@push('scripts')
<script>
    function bukaMModal(url) {
        document.getElementById('formBatal').action = url;
        const modal = document.getElementById('modalBatal');
        modal.style.display = 'flex';
    }

    function tutupModal() {
        document.getElementById('modalBatal').style.display = 'none';
    }

    document.getElementById('modalBatal').addEventListener('click', function(e) {
        if (e.target === this) tutupModal();
    });
</script>
@endpush
@endsection