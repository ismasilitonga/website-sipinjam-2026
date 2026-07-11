@extends('layouts.admin')

@section('title', 'Validasi Pendaftar')
@section('subtitle', 'Setujui atau tolak pendaftaran akun baru')

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Daftar Pendaftar Baru</span>
        <span class="badge badge-orange">{{ $pendaftars->total() }} menunggu</span>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;color:#15803d;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:13.5px;">
            {{ session('success') }}
        </div>
    @endif

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
                    <th>Bukti</th>
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
                    <td style="font-weight:500;">{{ $user->nama }}</td>
                    <td style="font-size:13px;font-family:monospace;">{{ $user->nim }}</td>
                    <td style="font-size:13px;">{{ $user->email }}</td>
                    <td style="font-size:13px;">{{ $user->organisasi ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'ketua' ? 'badge-blue' : 'badge-gray' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            @if($user->bukti_ktm)
                                <a href="{{ asset('storage/' . $user->bukti_ktm) }}" target="_blank"
                                   style="font-size:12px;color:var(--primary,#2563eb);text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                    </svg>
                                    Lihat KTM
                                </a>
                            @else
                                <span style="font-size:12px;color:var(--text-muted);">KTM belum ada</span>
                            @endif

                            @if($user->bukti_sk)
                                <a href="{{ asset('storage/' . $user->bukti_sk) }}" target="_blank"
                                   style="font-size:12px;color:var(--primary,#2563eb);text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                    </svg>
                                    Lihat SK
                                </a>
                            @else
                                <span style="font-size:12px;color:var(--text-muted);">SK belum ada</span>
                            @endif
                        </div>
                    </td>
                    <td style="font-size:13px;">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
    <div style="display:flex;gap:6px;">
        <button type="button" class="btn btn-success btn-sm"
            onclick="bukaModalSetujui('{{ $user->id }}', '{{ addslashes($user->nama) }}')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Setujui
        </button>

        <button type="button" class="btn btn-danger btn-sm"
            onclick="bukaModalTolak('{{ $user->id }}', '{{ addslashes($user->nama) }}')">
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
                    <td colspan="9">
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

<div id="modalGantiKetua" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px 22px;width:100%;max-width:340px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

        <div style="width:44px;height:44px;border-radius:50%;background:#fef3c7;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
            <svg width="22" height="22" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.008v.008H12v-.008z"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:18px;">
            <div style="font-size:15px;font-weight:700;margin-bottom:8px;">Sudah Ada Ketua Aktif</div>
            <div style="font-size:13px;color:var(--text-muted);line-height:1.5;">
                Organisasi <strong id="modalOrganisasiConflict"></strong> saat ini masih memiliki Ketua aktif:
                <strong id="modalKetuaLamaNama"></strong>.<br><br>
                Setujui <strong id="modalPendaftarNama"></strong> sebagai penggantinya?
                Ketua lama akan otomatis dinonaktifkan.
            </div>
        </div>

        <div style="display:flex;gap:8px;">
            <button type="button" onclick="tutupModalGantiKetua()"
                style="flex:1;padding:9px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:13px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formGantiKetua').submit()"
                style="flex:1;padding:9px;border:none;border-radius:8px;
                       background:#d97706;color:#fff;font-size:13px;font-weight:600;cursor:pointer;">
                Ya, Ganti Kepengurusan
            </button>
        </div>
    </div>
</div>

<form id="formGantiKetua" method="POST" style="display:none;">
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

function tutupModalGantiKetua() {
    document.getElementById('modalGantiKetua').style.display = 'none';
}

document.getElementById('modalSetujuiPendaftar').addEventListener('click', function(e) {
    if (e.target === this) tutupModalSetujuiPendaftar();
});
document.getElementById('modalTolakPendaftar').addEventListener('click', function(e) {
    if (e.target === this) tutupModalTolakPendaftar();
});
document.getElementById('modalGantiKetua').addEventListener('click', function(e) {
    if (e.target === this) tutupModalGantiKetua();
});

@if(session('ketua_conflict'))

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('modalOrganisasiConflict').textContent = @json(session('ketua_conflict')['organisasi']);
        document.getElementById('modalKetuaLamaNama').textContent = @json(session('ketua_conflict')['ketua_lama_nama']);
        document.getElementById('modalPendaftarNama').textContent = @json(session('ketua_conflict')['pendaftar_nama']);
        document.getElementById('formGantiKetua').action =
            `${baseUrlPendaftar}/{{ session('ketua_conflict')['pendaftar_id'] }}/ganti-kepengurusan`;
        document.getElementById('modalGantiKetua').style.display = 'flex';
    });
@endif
</script>

@endsection