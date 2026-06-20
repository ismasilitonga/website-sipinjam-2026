@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('subtitle', 'Buat akun pengguna baru langsung aktif')

@section('topbar-action')
    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">Form Pengguna Baru</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengguna.store') }}">
            @csrf

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('name') }}" placeholder="Nama lengkap pengguna" required>
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NIM <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nim" class="form-control"
                           value="{{ old('nim') }}" placeholder="Nomor Induk Mahasiswa" required>
                    @error('nim') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email <span style="color:var(--danger)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" placeholder="email@example.com" required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Role <span style="color:var(--danger)">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="anggota"  {{ old('role') === 'anggota'  ? 'selected' : '' }}>Anggota</option>
                        <option value="ketua"    {{ old('role') === 'ketua'    ? 'selected' : '' }}>Ketua</option>
                        <option value="pic"      {{ old('role') === 'pic'      ? 'selected' : '' }}>PIC</option>
                        <option value="pamdal"   {{ old('role') === 'pamdal'   ? 'selected' : '' }}>Pamdal</option>
                        <option value="admin"    {{ old('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Organisasi <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="organisasi" class="form-control"
                           value="{{ old('organisasi') }}" placeholder="Nama ormawa / unit" required>
                    @error('organisasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="password" class="form-control"
                       placeholder="Minimal 8 karakter" required>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">Akun yang dibuat admin langsung berstatus aktif.</div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengguna
                </button>
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
