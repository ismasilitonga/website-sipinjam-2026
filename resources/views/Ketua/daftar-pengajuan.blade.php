@extends('layouts.ketua')

@section('title', 'Kelola Pengajuan')
@section('subtitle', 'Tinjau dan setujui pengajuan dari anggota ormawa')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Pengajuan Menunggu Persetujuan</span>
        <span class="badge badge-yellow">{{ $pengajuans->total() }} menunggu</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Ruangan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Keperluan</th>
                    <th>Diajukan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $p)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:9px;">
                            <div style="width:30px;height:30px;border-radius:50%;background:#dcfce7;
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:12px;font-weight:700;color:#15803d;flex-shrink:0;">
                                {{ strtoupper(substr($p->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->user->name ?? '-' }}</div>
                                <div style="font-size:11.5px;color:var(--text-muted);">{{ $p->user->nim ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">
                            {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->gedung, $p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;">
                        <div style="font-weight:500;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</div>
                        <div style="color:var(--text-muted);">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                        </div>
                    </td>
                    <td style="font-size:13px;max-width:200px;">
                        <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;"
                             title="{{ $p->keperluan }}">
                            {{ $p->keperluan }}
                        </div>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">
                        {{ $p->created_at->diffForHumans() }}
                    </td>
                    <td>
                    <div style="display:flex;gap:6px;">

                            <button type="button" class="btn btn-success btn-sm"
    onclick="openSetujuiModal({{ $p->id }}, '{{ addslashes($p->user->name ?? '') }}')">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    Setujui
</button>
                            </form>

                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="openTolakModal({{ $p->id }}, '{{ addslashes($p->user->name ?? '') }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    
                    <td colspan="7">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Tidak ada pengajuan yang menunggu persetujuanmu.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengajuans->hasPages())
    <div class="pagination-wrap">{{ $pengajuans->links() }}</div>
    @endif
</div>

<div class="modal-overlay" id="tolakModal">
    <div class="modal-box">
        <div class="modal-title">
            <span style="color:var(--danger);">✕</span> Tolak Pengajuan
        </div>
        <p style="font-size:13.5px;color:var(--text-muted);margin-bottom:18px;">
            Masukkan alasan penolakan untuk
            <strong id="modalPeminjamName" style="color:var(--text);">—</strong>.
            Alasan ini akan dicatat pada sistem.
        </p>

        <form method="POST" id="tolakForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                <textarea name="alasan_tolak" class="form-control"
                          placeholder="Contoh: Jadwal bentrok dengan kegiatan lain..."
                          required></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="setujuiModal">
    <div class="modal-box">
        <div class="modal-title">
            <span style="color:var(--success);">✓</span> Setujui Pengajuan
        </div>
        <p style="font-size:13.5px;color:var(--text-muted);margin-bottom:18px;">
            Anda akan menyetujui dan meneruskan pengajuan
            <strong id="modalSetujuiPeminjamName" style="color:var(--text);">—</strong>
            ke PIC.
        </p>

        <form method="POST" id="setujuiForm">
            @csrf
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeSetujuiModal()">Batal</button>
                <button type="submit" class="btn btn-success">Ya, Setujui</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTolakModal(id, nama) {
        document.getElementById('tolakForm').action = '/ketua/daftar-pengajuan/' + id + '/tolak';
        document.getElementById('modalPeminjamName').textContent = nama;
        document.getElementById('tolakModal').classList.add('open');
    }
    function closeTolakModal() {
        document.getElementById('tolakModal').classList.remove('open');
        document.getElementById('tolakForm').querySelector('textarea').value = '';
    }

    document.getElementById('tolakModal').addEventListener('click', function(e) {
        if (e.target === this) closeTolakModal();
    });
    function openSetujuiModal(id, nama) {
    document.getElementById('setujuiForm').action = '/ketua/daftar-pengajuan/' + id + '/setujui';
    document.getElementById('modalSetujuiPeminjamName').textContent = nama;
    document.getElementById('setujuiModal').classList.add('open');
}
function closeSetujuiModal() {
    document.getElementById('setujuiModal').classList.remove('open');
}

document.getElementById('setujuiModal').addEventListener('click', function(e) {
    if (e.target === this) closeSetujuiModal();
});
</script>
@endpush

@endsection
