@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('subtitle', 'Informasi lengkap akun pengguna')

@section('topbar-action')
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-outline">Kembali</a>
    </div>
@endsection

@section('content')

<style>
    .user-detail-page .detail-row {
        display: flex;
        align-items: center;
    }
    .user-detail-page .detail-label {
        flex: 0 0 180px;
        max-width: 180px;
        white-space: nowrap;
    }
    .user-detail-page .detail-value {
        flex: 1;
        min-width: 0;
    }
    @media (max-width: 480px) {
        .user-detail-page .detail-label {
            flex: 0 0 130px;
            max-width: 130px;
        }
    }
</style>

<div class="user-detail-page" style="max-width:700px;margin:0 auto;">

    @php
        $tahunSekarang = (int) date('Y');
        $periodeHabis = $user->periode_selesai && $user->periode_selesai < $tahunSekarang;
    @endphp

    <div class="card">
        <div class="card-body" style="text-align:center;padding:32px 20px;">
            <div style="width:80px;height:80px;border-radius:50%;background:#dbeafe;
                        display:flex;align-items:center;justify-content:center;
                        font-size:32px;font-weight:700;color:#1d4ed8;margin:0 auto 16px;">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div style="font-family:'Outfit',sans-serif;font-size:17px;font-weight:600;">
                {{ $user->nama }}
            </div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">
                {{ $user->email }}
            </div>
            <div style="margin-top:12px;">
                @php
                    $roleClass = match($user->role) {
                        'admin'  => 'badge-red',
                        'ketua'  => 'badge-blue',
                        'pic'    => 'badge-purple',
                        'pamdal' => 'badge-orange',
                        default  => 'badge-gray',
                    };
                @endphp
                <span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                <span class="badge {{ $user->status === 'aktif' ? 'badge-green' : ($user->status === 'ditolak' ? 'badge-red' : 'badge-orange') }}" style="margin-left:4px;">
                {{ $user->status === 'aktif' ? 'Aktif' : ($user->status === 'ditolak' ? 'Ditolak' : 'Menunggu Validasi') }}
            </span>
            @if ($periodeHabis)
                <span class="badge badge-red" style="margin-left:4px;">⚠ Masa Jabatan Berakhir</span>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Informasi Akun</span>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="detail-row">
                <div class="detail-label">NIM</div>
                <div class="detail-value" style="font-family:monospace;">{{ $user->nim }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nama Lengkap</div>
                <div class="detail-value">{{ $user->nama }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $user->email }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Role</div>
                <div class="detail-value">
                    <span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Organisasi</div>
                <div class="detail-value">{{ $user->organisasi ?? '-' }}</div>
            </div>
            <div class="detail-row">
            <div class="detail-label">Periode Kepengurusan</div>
            <div class="detail-value">
        @if ($user->periode_mulai && $user->periode_selesai)
            @php
                $mulaiLabel = \Carbon\Carbon::createFromDate($user->periode_mulai, $user->periode_mulai_bulan ?? 1, 1)
                    ->translatedFormat('F Y');
                $selesaiLabel = \Carbon\Carbon::createFromDate($user->periode_selesai, $user->periode_selesai_bulan ?? 12, 1)
                    ->translatedFormat('F Y');
            @endphp
            {{ $mulaiLabel }} &ndash; {{ $selesaiLabel }}
            @if ($periodeHabis)
                <span class="badge badge-red" style="margin-left:6px;">Berakhir</span>
            @endif
            @else
            <span style="color:var(--text-muted);">Belum diatur</span>
            @endif
        </div>
        </div>
            <div class="detail-row">
                <div class="detail-label">Dokumen Pendaftaran</div>
                <div class="detail-value">
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        @if($user->bukti_ktm)
                            <a href="{{ asset('storage/' . $user->bukti_ktm) }}" target="_blank"
                               style="font-size:13px;color:var(--primary,#2563eb);text-decoration:none;display:flex;align-items:center;gap:4px;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                </svg>
                                Lihat KTM
                            </a>
                        @else
                            <span style="font-size:13px;color:var(--text-muted);">KTM belum ada</span>
                        @endif

                        @if($user->bukti_sk)
                            <a href="{{ asset('storage/' . $user->bukti_sk) }}" target="_blank"
                               style="font-size:13px;color:var(--primary,#2563eb);text-decoration:none;display:flex;align-items:center;gap:4px;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                </svg>
                                Lihat SK
                            </a>
                        @else
                            <span style="font-size:13px;color:var(--text-muted);">SK belum ada</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status Akun</div>
                <div class="detail-value">
                    <span class="badge {{ $user->status === 'aktif' ? 'badge-green' : ($user->status === 'ditolak' ? 'badge-red' : 'badge-orange') }}">
                        {{ $user->status === 'aktif' ? 'Aktif' : ($user->status === 'ditolak' ? 'Ditolak' : 'Menunggu Validasi') }}
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Bergabung</div>
                <div class="detail-value">{{ $user->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Terakhir Diperbarui</div>
                <div class="detail-value">{{ $user->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>
</div>


<div class="card" style="margin-top:20px;border-color:#fee2e2;">
    <div class="card-header">
        <span class="card-title" style="color:var(--danger);">Zona Berbahaya</span>
    </div>
    <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="font-size:14px;font-weight:500;">Hapus Pengguna</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">
                Tindakan ini permanen dan tidak dapat dibatalkan.
            </div>
        </div>
        <button type="button" class="btn btn-danger" onclick="bukaModalHapusPengguna()">
            Hapus Pengguna
        </button>
    </div>
</div>

<div id="modalHapusPengguna" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
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
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Hapus Pengguna?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menghapus pengguna <strong>{{ $user->nama }}</strong>.
                Tindakan ini <strong>permanen</strong> dan tidak dapat dibatalkan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalHapusPengguna()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formHapusPengguna').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<form id="formHapusPengguna" method="POST" action="{{ route('admin.pengguna.destroy', $user->id) }}" style="display:none;">
    @csrf @method('DELETE')
</form>

<script>
function bukaModalHapusPengguna() {
    document.getElementById('modalHapusPengguna').style.display = 'flex';
}
function tutupModalHapusPengguna() {
    document.getElementById('modalHapusPengguna').style.display = 'none';
}
document.getElementById('modalHapusPengguna').addEventListener('click', function(e) {
    if (e.target === this) tutupModalHapusPengguna();
});
</script>

@endsection