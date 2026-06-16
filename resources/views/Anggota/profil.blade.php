@extends('layouts.anggota')

@section('title', 'Profil Saya')

@section('content')

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start;">

    <div class="card" style="text-align:center;">
        <div class="card-body" style="padding:32px 20px;">
            <div style="width:80px;height:80px;border-radius:50%;
                        background:linear-gradient(135deg,#4f46e5,#06b6d4);
                        display:flex;align-items:center;justify-content:center;
                        font-family:'Sora',sans-serif;font-size:32px;font-weight:700;
                        color:#fff;margin:0 auto 16px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="font-family:'Sora',sans-serif;font-size:17px;font-weight:700;">{{ $user->name }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">{{ $user->email }}</div>
            <div style="margin-top:12px;">
                <span class="badge badge-blue">{{ ucfirst($user->role) }}</span>
                <span class="badge badge-green" style="margin-left:4px;">Aktif</span>
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);
                        font-size:12px;color:var(--text-muted);">
                Bergabung {{ $user->created_at->format('M Y') }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('anggota.profil.update') }}">
                @csrf

                <div class="form-grid-2">
                    <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text"
                    class="form-control"
                    value="{{ $user->name }}"
                    readonly>
                </div>

                    <div class="form-group">
                        <label class="form-label">NIM</label>
                        <input type="text"
                        class="form-control"
                        value="{{ $user->nim }}"
                        readonly>
                    </div>
                </div>

             <div class="form-grid-2">

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email"
                    class="form-control"
                    value="{{ $user->email }}"
                    readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Organisasi</label>
                    <input type="text"
                    class="form-control"
                    value="{{ $user->organisasi }}"
                    readonly>
                </div>
            </div>

        <hr style="border:none;border-top:1px solid var(--border);margin:15px 0;">
        <div style="
            font-family:'Sora',sans-serif;
            font-size:13px;
            font-weight:700;
            margin-bottom:14px;
            color:var(--text-muted);
        ">
    GANTI PASSWORD
</div>

<div class="form-grid-2">

    <div class="form-group">
        <label class="form-label">Password Lama</label>
        <input type="password"
               name="current_password"
               class="form-control"
               placeholder="Masukkan password lama">
        @error('current_password')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Password Baru</label>
        <input type="password"
               name="new_password"
               class="form-control"
               placeholder="Minimal 8 karakter">
        @error('new_password')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>
</div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password"
            name="new_password_confirmation"
            class="form-control"
            placeholder="Ulangi password baru">
                </div>
                <div class="form-hint" style="margin-top:-8px;margin-bottom:18px;">
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
