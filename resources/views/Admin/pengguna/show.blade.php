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

<div style="max-width:700px;margin:0 auto;">

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
        <form method="POST" action="{{ route('admin.pengguna.destroy', $user->id) }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger"
                onclick="return confirm('Hapus pengguna {{ $user->nama }}? Tindakan tidak bisa dibatalkan.')">
                Hapus Pengguna
            </button>
        </form>
    </div>
</div>

@endsection
