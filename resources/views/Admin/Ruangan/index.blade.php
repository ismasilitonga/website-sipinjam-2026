@extends('layouts.admin')

@section('title', 'Kelola Ruangan')
@section('subtitle', 'Tambah, edit, dan hapus data ruangan')

@section('topbar-action')
    <a href="{{ route('admin.ruangan.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Ruangan
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Ruangan</span>
        <span class="badge badge-gray">{{ $ruangans->total() }} ruangan</span>
    </div>
   <div class="table-wrap" style="overflow-x:auto;">
    <table style="width:100%;">
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th style="width:60px;">Foto</th>
                <th style="width:150px;">Nama Ruangan</th>
                <th style="width:90px;">Kode</th>
                <th style="width:150px;">Gedung / Lantai</th>
                <th style="width:90px;">Kapasitas</th>
                <th style="width:180px;">Fasilitas</th>
                <th style="width:120px;">Status</th>
                <th style="width:120px;">Aksi</th>
            </tr>
            </thead>
            <tbody>
                @forelse($ruangans as $ruangan)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($ruangans->currentPage() - 1) * $ruangans->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        @if($ruangan->foto)
                            <img src="{{ asset('storage/' . $ruangan->foto) }}" alt="{{ $ruangan->nama_ruangan }}"
                                 style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                        @else
                            <div style="width:48px;height:48px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#94a3b8;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:500;font-size:13px;">{{ $ruangan->nama_ruangan}}</td>
                    <td style="font-size:12.5px;color:var(--text-muted);">{{ $ruangan->kode }}</td>
                    <td style="font-size:12.5px;">
                        {{ $ruangan->gedung ?? '-' }}
                        @if($ruangan->lantai) · Lt. {{ $ruangan->lantai }} @endif
                    </td>
                    <td style="font-size:12.5px;">
                        <span style="font-weight:600;">{{ $ruangan->kapasitas }}</span>
                        <span style="color:var(--text-muted);font-size:11px;"> org</span>
                    </td>
                    <td style="font-size:12.5px;max-width:160px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $ruangan->fasilitas }}">
                            {{ $ruangan->fasilitas ?? '-' }}
                        </div>
                    </td>
                   <td>
    @php
        $sCls = match($ruangan->status) {
            'tersedia'       => 'badge-green',
            'tidak_tersedia' => 'badge-red',
            default          => 'badge-gray',
        };
        $sLabel = match($ruangan->status) {
            'tersedia'       => 'Tersedia',
            'tidak_tersedia' => 'Tidak Tersedia',
            default          => ucfirst($ruangan->status),
        };
    @endphp
    <span class="badge {{ $sCls }}" style="white-space:nowrap; display:inline-block;">{{ $sLabel }}</span>
</td>
                    <td>
    <div style="display:flex;gap:6px;">
        <a href="{{ route('admin.ruangan.edit', $ruangan->id) }}"
           class="btn btn-outline btn-sm">Edit</a>
        <button type="button" class="btn btn-danger btn-sm"
            onclick="bukaModalRuangan('{{ $ruangan->id }}', '{{ addslashes($ruangan->nama_ruangan) }}')">
            Hapus
        </button>
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <p>Belum ada ruangan yang terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ruangans->hasPages())
    <div class="pagination-wrap">{{ $ruangans->links('layouts.pagination') }}</div>
    @endif
</div>

<div id="modalHapusRuangan" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

        <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Hapus Ruangan?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menghapus ruangan <strong id="modalNamaRuangan"></strong>.
                Tindakan ini <strong>permanen</strong> dan tidak dapat dibatalkan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalRuangan()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formHapusRuangan').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<form id="formHapusRuangan" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
const baseUrlRuangan = "{{ url('admin/ruangan') }}";

function bukaModalRuangan(id, nama_ruangan) {
    document.getElementById('modalNamaRuangan').textContent = nama_ruangan;
    document.getElementById('formHapusRuangan').action = `${baseUrlRuangan}/${id}`;
    document.getElementById('modalHapusRuangan').style.display = 'flex';
}

function tutupModalRuangan() {
    document.getElementById('modalHapusRuangan').style.display = 'none';
}

document.getElementById('modalHapusRuangan').addEventListener('click', function(e) {
    if (e.target === this) tutupModalRuangan();
});
</script>

@endsection