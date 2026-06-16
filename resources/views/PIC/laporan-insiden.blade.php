@extends('layouts.pic')

@section('title', 'Laporan Insiden')
@section('subtitle', 'Tindak lanjuti laporan insiden dari anggota')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Laporan Insiden</span>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('pic.laporan-insiden.pdf') }}" class="btn btn-primary btn-sm">
                📄 PDF
            </a>
            <a href="{{ route('pic.laporan-insiden.excel') }}" class="btn btn-sm"
               style="background:#163b72;color:white;">
                📊 Excel
            </a>
        </div>
    </div>

    <div class="table-wrap" style="overflow-x:auto;">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:130px;">Pelapor</th>
                    <th style="width:200px;">Judul</th>
                    <th style="width:150px;">Lokasi</th>
                    <th style="width:130px;">Dilaporkan</th>
                    <th style="width:130px;">Status</th>
                    <th style="width:160px;">Tindak Lanjut</th>
                    <th style="width:130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insidens as $ins)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($insidens->currentPage() - 1) * $insidens->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;white-space:nowrap;">{{ $ins->user->name ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $ins->user->organisasi ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:13px;">{{ $ins->judul }}</div>
                        @if($ins->foto)
                        <a href="{{ asset('storage/'.$ins->foto) }}" target="_blank"
                           style="font-size:11.5px;color:var(--accent);text-decoration:none;">
                            📷 Lihat foto
                        </a>
                        @endif
                    </td>
                    <td style="font-size:12.5px;">{{ $ins->lokasi }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">
                        {{ $ins->created_at->format('d M Y') }}<br>
                        {{ $ins->created_at->format('H:i') }}
                    </td>
                    <td>
                        @php
                            [$cls, $lbl] = match($ins->status) {
                                'dilaporkan'      => ['badge-orange', 'Dilaporkan'],
                                'ditindaklanjuti' => ['badge-blue',   'Ditindaklanjuti'],
                                'selesai'         => ['badge-green',  'Selesai'],
                                default           => ['badge-gray',   ucfirst($ins->status)],
                            };
                        @endphp
                        <span class="badge {{ $cls }}" style="white-space:nowrap;">{{ $lbl }}</span>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">
                        @if($ins->tindak_lanjut)
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                 title="{{ $ins->tindak_lanjut }}">
                                {{ Str::limit($ins->tindak_lanjut, 50) }}
                            </div>
                            @if($ins->waktu_ditindak)
                            <div style="font-size:11px;margin-top:2px;">
                                {{ \Carbon\Carbon::parse($ins->waktu_ditindak)->format('d M, H:i') }}
                            </div>
                            @endif
                        @else
                            <span style="color:#d4d4d8;">Belum ada</span>
                        @endif
                    </td>
                    <td>
                        @if($ins->status !== 'selesai')
                        <button type="button" class="btn btn-primary btn-sm" style="white-space:nowrap;"
                            onclick="openTindakModal(
                                {{ $ins->id }},
                                '{{ addslashes($ins->judul) }}',
                                '{{ addslashes($ins->tindak_lanjut ?? '') }}',
                                '{{ $ins->status }}'
                            )">
                            ✏️ Tindak Lanjut
                        </button>
                        @else
                        <span class="badge badge-green" style="white-space:nowrap;">✓ Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
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
    <div class="pagination-wrap">
        {{ $insidens->links('layouts.pagination') }}
    </div>
    @endif
</div>

<div class="modal-overlay" id="tindakModal">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-title">Tindak Lanjut Insiden</div>
        <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">
            Insiden: <strong id="judulInsiden" style="color:var(--text);">—</strong>
        </p>
        <form method="POST" id="tindakForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Keterangan Tindak Lanjut <span style="color:var(--danger)">*</span></label>
                <textarea name="tindak_lanjut" id="tindakLanjutText" class="form-control"
                          placeholder="Deskripsikan tindakan yang telah atau akan dilakukan..." required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Update Status <span style="color:var(--danger)">*</span></label>
                <select name="status" id="tindakStatus" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Status --</option>
                    <option value="ditindaklanjuti">Ditindaklanjuti (masih dalam proses)</option>
                    <option value="selesai">Selesai (insiden telah tuntas)</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeTindakModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Tindak Lanjut</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTindakModal(id, judul, tindakLanjut, status) {
        document.getElementById('tindakForm').action = '/pic/laporan-insiden/' + id;
        document.getElementById('judulInsiden').textContent = judul;
        document.getElementById('tindakLanjutText').value = tindakLanjut;

        const statusSelect = document.getElementById('tindakStatus');
        if (status === 'dilaporkan') {
            statusSelect.value = "";
        } else {
            statusSelect.value = status === 'selesai' ? 'selesai' : 'ditindaklanjuti';
        }

        document.getElementById('tindakModal').classList.add('open');
    }
    function closeTindakModal() {
        document.getElementById('tindakModal').classList.remove('open');
    }
    document.getElementById('tindakModal').addEventListener('click', function(e) {
        if (e.target === this) closeTindakModal();
    });
</script>
@endpush

@endsection