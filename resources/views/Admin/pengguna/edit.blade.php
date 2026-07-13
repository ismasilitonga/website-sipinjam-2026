@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('subtitle', 'Perbarui data akun: ' . $user->nama)

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
        <span class="card-title">Edit Data Pengguna</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengguna.update', $user->id) }}">
            @csrf @method('PUT')

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $user->nama) }}" required>
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NIM <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nim" class="form-control"
                           value="{{ old('nim', $user->nim) }}" required>
                    @error('nim') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email <span style="color:var(--danger)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Role <span style="color:var(--danger)">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="anggota"  {{ old('role', $user->role) === 'anggota'  ? 'selected' : '' }}>Anggota</option>
                        <option value="ketua"    {{ old('role', $user->role) === 'ketua'    ? 'selected' : '' }}>Ketua</option>
                        <option value="pic"      {{ old('role', $user->role) === 'pic'      ? 'selected' : '' }}>PIC</option>
                        <option value="pamdal"   {{ old('role', $user->role) === 'pamdal'   ? 'selected' : '' }}>Pamdal</option>
                        <option value="admin"    {{ old('role', $user->role) === 'admin'    ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Organisasi <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="organisasi" class="form-control"
                           value="{{ old('organisasi', $user->organisasi) }}" required>
                    @error('organisasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            @php
                $periodeMulaiValue = old('periode_mulai',
                    $user->periode_mulai
                        ? sprintf('%04d-%02d', $user->periode_mulai, $user->periode_mulai_bulan ?? 1)
                        : null
                );
                $periodeSelesaiValue = old('periode_selesai',
                    $user->periode_selesai
                        ? sprintf('%04d-%02d', $user->periode_selesai, $user->periode_selesai_bulan ?? 12)
                        : null
                );
            @endphp

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Periode Mulai</label>
                    <input type="month" name="periode_mulai" class="form-control"
                           value="{{ $periodeMulaiValue }}"
                           min="2020-01" max="2035-12">
                    @error('periode_mulai') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Periode Selesai</label>
                    <input type="month" name="periode_selesai" class="form-control"
                           value="{{ $periodeSelesaiValue }}"
                           min="2020-01" max="2035-12">
                    @error('periode_selesai') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Akun <span style="color:var(--danger)">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="aktif"    {{ old('status', $user->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $user->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="pending"  {{ old('status', $user->status) === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="ditolak"  {{ old('status', $user->status) === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                </select>
                @error('status') <div class="form-error">{{ $message }}</div> @enderror

                @if ($user->masaJabatanBerakhir())
                    <div class="form-hint" style="color:var(--danger);margin-top:6px;">
                        ⚠ Masa jabatan berakhir pada {{ $user->batasAkhirJabatan()->translatedFormat('F Y') }}. Pertimbangkan ubah status menjadi Nonaktif.
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Kosongkan jika tidak ingin mengubah">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">Isi hanya jika ingin mengganti password.</div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.pengguna.show', $user->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection