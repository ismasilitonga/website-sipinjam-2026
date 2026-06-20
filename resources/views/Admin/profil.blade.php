@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('subtitle', 'Kelola informasi akun admin')

@section('content')

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start;">

    <div class="card" style="text-align:center;">
        <div class="card-body" style="padding:32px 20px;">
            <div style="width:80px;height:80px;border-radius:50%;
                        background:linear-gradient(135deg,#2563eb,#06b6d4);
                        display:flex;align-items:center;justify-content:center;
                        font-family:'Outfit',sans-serif;font-size:32px;font-weight:700;
                        color:#fff;margin:0 auto 16px;">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div style="font-family:'Outfit',sans-serif;font-size:17px;font-weight:700;">{{ $user->nama }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">{{ $user->email }}</div>
            <div style="margin-top:10px;">
                <span class="badge badge-blue">Admin</span>
                <span class="badge badge-green" style="margin-left:4px;">Aktif</span>
            </div>
            <div style="margin-top:14px;font-size:12px;color:var(--text-muted);">
                Bergabung {{ $user->created_at->format('M Y') }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Edit Profil</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.profil.update') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $user->nama) }}" required>
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color:var(--danger)">*</span></label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">
                <div style="font-size:12px;font-weight:700;color:var(--text-muted);letter-spacing:.5px;margin-bottom:14px;">
                    Ganti Password
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control"
                               placeholder="Minimal 8 karakter">
                        @error('new_password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control"
                               placeholder="Ulangi password baru">
                    </div>
                </div>
                <div class="form-hint" style="margin-top:-8px;margin-bottom:20px;">
                    Kosongkan jika tidak ingin mengganti password.
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

</div>
@endsection