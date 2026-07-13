@extends('layouts.pic')

@section('title', 'Validasi Pengajuan Ruangan')
@section('subtitle', 'Setujui atau Tolak Pengajuan Peminjaman dari Ketua Ormawa')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Pengajuan Menunggu Validasi PIC</span>
        <span class="badge badge-purple">{{ $peminjaman_ruangans->total() }} pengajuan</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Ormawa</th>
                    <th>Ruangan</th>
                    <th>Tanggal & Waktu</th>
                    <th>Keperluan</th>
                    <th>Dokumen</th>
                    <th>Diajukan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman_ruangans as $p)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($peminjaman_ruangans->currentPage() - 1) * $peminjaman_ruangans->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;border-radius:50%;background:#ede9fe;
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:12px;font-weight:700;color:#7c3aed;flex-shrink:0;">
                                {{ strtoupper(substr($p->user->nama ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->user->nama ?? '-' }}</div>
                                <div style="font-size:11px;color:var(--text-muted);font-family:monospace;">{{ $p->user->nim ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12.5px;">{{ $p->nama_ormawa ?? $p->user?->organisasi ?? '-' }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $p->ruangan->nama_ruangan ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">
                            {{ $p->ruangan->gedung ?? '' }}{{ isset($p->ruangan->lantai) ? ' · Lt.'.$p->ruangan->lantai : '' }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;white-space:nowrap;">
                        <div style="font-weight:500;">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</div>
                        <div style="color:var(--text-muted);">
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i') }}
                        </div>
                    </td>
                    <td style="font-size:12.5px;max-width:180px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $p->keperluan }}">
                            {{ $p->keperluan }}
                        </div>
                    </td>
                    {{-- === BARU: kolom dokumen pendukung, biar PIC bisa nilai prioritas/urgensi pengajuan === --}}
                    <td style="font-size:12px;">
                        @if($p->dokumen_pendukung)
                            <a href="{{ asset('storage/' . $p->dokumen_pendukung) }}" target="_blank"
                               style="display:inline-flex;align-items:center;gap:4px;color:var(--accent);
                                      text-decoration:none;font-weight:600;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lihat Dokumen
                            </a>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;color:var(--text-muted);
                                         background:#f3f4f6;padding:3px 8px;border-radius:6px;font-size:10px;">
                                Tanpa dokumen
                            </span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $p->created_at->diffForHumans() }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button type="button" class="btn btn-success btn-sm"
                                onclick="openSetujuiModal({{ $p->id }}, '{{ addslashes($p->user->nama ?? '') }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setujui
                            </button>

                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="openTolakModal({{ $p->id }}, '{{ addslashes($p->user->nama ?? '') }}')">
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
                    <td colspan="9">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Tidak ada pengajuan yang menunggu validasi PIC.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjaman_ruangans->hasPages())
    <div style="margin-top:16px;">
        {{ $peminjaman_ruangans->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

{{-- Modal Konfirmasi Setujui --}}
<div id="setujuiModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

        <div style="width:52px;height:52px;border-radius:50%;background:#dcfce7;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Setujui Pengajuan?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menyetujui pengajuan dari <strong id="namaSetujui"></strong>.
                Peminjam akan mendapat notifikasi persetujuan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="closeSetujuiModal()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formSetujui').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#16a34a;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Setujui
            </button>
        </div>
    </div>
</div>

<form id="formSetujui" method="POST" style="display:none;">
    @csrf
</form>

{{-- Modal Tolak (sudah ada, tetap dipertahankan) --}}
<div class="modal-overlay" id="tolakModal">
    <div class="modal-box">
        <div class="modal-title">Tolak Pengajuan</div>
        <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">
            Berikan alasan penolakan untuk <strong id="namaPeminjam">—</strong>.
        </p>
        <form method="POST" id="tolakForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                <textarea name="alasan_tolak" class="form-control"
                          placeholder="Contoh: Ruangan sedang dalam perbaikan..." required></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ── Setujui ──────────────────────────────────────────
    function openSetujuiModal(id, nama) {
        document.getElementById('namaSetujui').textContent = nama;
        document.getElementById('formSetujui').action = '/pic/daftar-pengajuan/' + id + '/setujui';
        document.getElementById('setujuiModal').style.display = 'flex';
    }
    function closeSetujuiModal() {
        document.getElementById('setujuiModal').style.display = 'none';
    }
    document.getElementById('setujuiModal').addEventListener('click', function(e) {
        if (e.target === this) closeSetujuiModal();
    });

    // ── Tolak ────────────────────────────────────────────
    function openTolakModal(id, nama) {
        document.getElementById('tolakForm').action = '/pic/daftar-pengajuan/' + id + '/tolak';
        document.getElementById('namaPeminjam').textContent = nama;
        document.getElementById('tolakModal').classList.add('open');
    }
    function closeTolakModal() {
        document.getElementById('tolakModal').classList.remove('open');
        document.getElementById('tolakForm').querySelector('textarea').value = '';
    }
    document.getElementById('tolakModal').addEventListener('click', function(e) {
        if (e.target === this) closeTolakModal();
    });
</script>
@endpush

@endsection