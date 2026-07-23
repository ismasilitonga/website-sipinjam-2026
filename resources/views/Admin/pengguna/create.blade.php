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
        <form method="POST" action="{{ route('admin.pengguna.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama') }}" placeholder="Nama lengkap pengguna" required>
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" id="label-nim">NIM <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nim" id="input-nim" class="form-control"
                           value="{{ old('nim') }}" placeholder="Nomor Induk Mahasiswa" required>
                    @error('nim') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group" id="wrap-dokumen" style="display:none;">
    <div class="form-grid-2">
        <div>
            <label class="form-label">Bukti KTM <span style="color:var(--danger)">*</span></label>
            <input type="file" name="bukti_ktm" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            @error('bukti_ktm') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label">Bukti SK Organisasi <span style="color:var(--danger)">*</span></label>
            <input type="file" name="bukti_sk" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            @error('bukti_sk') <div class="form-error">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="form-hint">Format JPG, PNG, atau PDF. Maksimal 5MB per file.</div>
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
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="anggota" {{ old('role') === 'anggota' ? 'selected' : '' }}>Anggota Ormawa</option>
                        <option value="ketua"   {{ old('role') === 'ketua'   ? 'selected' : '' }}>Ketua Ormawa</option>
                        <option value="pic"     {{ old('role') === 'pic'     ? 'selected' : '' }}>PIC</option>
                        <option value="pamdal"  {{ old('role') === 'pamdal'  ? 'selected' : '' }}>Pamdal</option>
                        <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" id="wrap-organisasi" style="display:none;">
                    <label class="form-label">Organisasi <span style="color:var(--danger)">*</span></label>
                    <select name="organisasi" id="select-organisasi" class="form-select">
                        <option value="">-- Pilih Organisasi --</option>
                        @foreach($ormawas as $o)
                            <option value="{{ $o->singkatan }}"
                                data-punya-pic="{{ $o->punya_pic ? '1' : '0' }}"
                                {{ old('organisasi') === $o->singkatan ? 'selected' : '' }}>
                                {{ $o->singkatan }} - {{ $o->nama_organisasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('organisasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" id="wrap-lantai" style="display:none;">
                    <label class="form-label">Lantai yang Dikelola <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="lantai_pic" class="form-control" min="1" max="20"
                           value="{{ old('lantai_pic') }}" placeholder="Contoh: 2">
                    @error('lantai_pic') <div class="form-error">{{ $message }}</div> @enderror
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect        = document.getElementById('role');
    const wrapOrganisasi    = document.getElementById('wrap-organisasi');
    const wrapLantai        = document.getElementById('wrap-lantai');
    const selectOrganisasi  = document.getElementById('select-organisasi');
    const orgOptions        = [...selectOrganisasi.options].filter(o => o.value !== '');

    function toggleFields() {
    const role = roleSelect.value;
    const wrapDokumen = document.getElementById('wrap-dokumen');

    if (role === 'anggota' || role === 'ketua') {
        wrapOrganisasi.style.display = 'block';
        wrapLantai.style.display = 'none';
        wrapDokumen.style.display = 'block';
        orgOptions.forEach(o => o.hidden = false);
        selectOrganisasi.value = '';

    } else if (role === 'pic') {
        wrapOrganisasi.style.display = 'block';
        wrapLantai.style.display = 'block';
        wrapDokumen.style.display = 'none';
        orgOptions.forEach(o => o.hidden = false);
        selectOrganisasi.value = '';

    } else {
        wrapOrganisasi.style.display = 'none';
        wrapLantai.style.display = 'none';
        wrapDokumen.style.display = 'none';
        selectOrganisasi.value = '';
    }
}

    roleSelect.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endpush

@endsection