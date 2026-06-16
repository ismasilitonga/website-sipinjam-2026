@extends('layouts.admin')

@section('title', 'Edit Ormawa')
@section('subtitle', 'Ubah data organisasi: ' . $ormawa->singkatan)

@section('topbar-action')
    <a href="{{ route('admin.ormawa.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="card" style="max-width:520px;">
    <div class="card-header">
        <span class="card-title">Edit Data Ormawa</span>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:16px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.ormawa.update', $ormawa->id) }}">
            @csrf @method('PUT')

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Singkatan <span style="color:red;">*</span></label>
                    <input type="text" name="singkatan" class="form-control"
                           value="{{ old('singkatan', $ormawa->singkatan) }}"
                           style="text-transform:uppercase;">
                    <div class="form-hint">Mengubah singkatan tidak mempengaruhi data anggota.</div>
                    @error('singkatan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span style="color:red;">*</span></label>
                    <select name="status" class="form-control">
                        <option value="aktif"    {{ old('status', $ormawa->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $ormawa->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap Organisasi</label>
                <input type="text" name="nama_organisasi" class="form-control"
                       value="{{ old('nama_organisasi', $ormawa->nama_organisasi) }}"
                       placeholder="Contoh: Kumpulan Anak Seni">
                @error('nama_organisasi') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kontak</label>
                <input type="text" name="kontak" class="form-control"
                       value="{{ old('kontak', $ormawa->kontak) }}"
                       placeholder="Contoh: 08123456789">
                @error('kontak') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Deskripsi singkat tentang ormawa ini...">{{ old('deskripsi', $ormawa->deskripsi) }}</textarea>
                @error('deskripsi') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.ormawa.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection