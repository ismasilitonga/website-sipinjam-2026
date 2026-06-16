@extends('layouts.admin')

@section('title', 'Validasi Pendaftar')
@section('subtitle', 'Setujui atau tolak pendaftaran akun baru')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Pendaftar Baru</span>
        <span class="badge badge-orange">{{ $pendaftars->total() }} menunggu</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Organisasi</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftars as $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px;">
                        {{ ($pendaftars->currentPage() - 1) * $pendaftars->perPage() + $loop->iteration }}
                    </td>
                    <td style="font-weight:500;">{{ $user->name }}</td>
                    <td style="font-size:13px;font-family:monospace;">{{ $user->nim }}</td>
                    <td style="font-size:13px;">{{ $user->email }}</td>
                    <td style="font-size:13px;">{{ $user->organisasi ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'ketua' ? 'badge-blue' : 'badge-gray' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="font-size:13px;">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
    <div style="display:flex;gap:6px;">
        {{-- Tombol Setujui: buka modal --}}
        <button type="button" class="btn btn-success btn-sm"
            onclick="bukaModalSetujui('{{ $user->id }}', '{{ addslashes($user->name) }}')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Setujui
        </button>

        {{-- Tombol Tolak: buka modal --}}
        <button type="button" class="btn btn-danger btn-sm"
            onclick="bukaModalTolak('{{ $user->id }}', '{{ addslashes($user->name) }}')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tolak
        </button>
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Tidak ada pendaftar yang menunggu validasi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendaftars->hasPages())
    <div class="pagination-wrap">
        {{ $pendaftars->links() }}
    </div>
    @endif
</div>

{{-- Modal Konfirmasi Setujui Pendaftar --}}
<div id="modalSetujuiPendaftar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
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
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Aktifkan Akun?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan mengaktifkan akun <strong id="modalNamaSetujui"></strong>.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalSetujuiPendaftar()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formSetujuiPendaftar').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#16a34a;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Aktifkan
            </button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Tolak Pendaftar --}}
<div id="modalTolakPendaftar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
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
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Tolak Pendaftar?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menolak dan menghapus pendaftar <strong id="modalNamaTolak"></strong>.
                Tindakan ini <strong>permanen</strong> dan tidak dapat dibatalkan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalTolakPendaftar()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formTolakPendaftar').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Tolak
            </button>
        </div>
    </div>
</div>

<form id="formSetujuiPendaftar" method="POST" style="display:none;">
    @csrf
</form>
<form id="formTolakPendaftar" method="POST" style="display:none;">
    @csrf
</form>

<script>
const baseUrlPendaftar = "{{ url('admin/pendaftar') }}";

function bukaModalSetujui(id, nama) {
    document.getElementById('modalNamaSetujui').textContent = nama;
    document.getElementById('formSetujuiPendaftar').action = `${baseUrlPendaftar}/${id}/setujui`;
    document.getElementById('modalSetujuiPendaftar').style.display = 'flex';
}
function tutupModalSetujuiPendaftar() {
    document.getElementById('modalSetujuiPendaftar').style.display = 'none';
}

function bukaModalTolak(id, nama) {
    document.getElementById('modalNamaTolak').textContent = nama;
    document.getElementById('formTolakPendaftar').action = `${baseUrlPendaftar}/${id}/tolak`;
    document.getElementById('modalTolakPendaftar').style.display = 'flex';
}
function tutupModalTolakPendaftar() {
    document.getElementById('modalTolakPendaftar').style.display = 'none';
}

document.getElementById('modalSetujuiPendaftar').addEventListener('click', function(e) {
    if (e.target === this) tutupModalSetujuiPendaftar();
});
document.getElementById('modalTolakPendaftar').addEventListener('click', function(e) {
    if (e.target === this) tutupModalTolakPendaftar();
});
</script>

@endsection
