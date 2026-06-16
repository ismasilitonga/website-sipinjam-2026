@extends('layouts.admin')

@section('title', 'Tambah Ormawa')
@section('subtitle', 'Daftarkan organisasi mahasiswa baru')

@section('topbar-action')
    <a href="{{ route('admin.ormawa.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">Form Tambah Ormawa</span>
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

        <form method="POST" action="{{ route('admin.ormawa.store') }}">
            @csrf

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Singkatan <span style="color:red;">*</span></label>
                    <input type="text" name="singkatan" class="form-control"
                           value="{{ old('singkatan') }}"
                           placeholder="Contoh: KUAS"
                           style="text-transform:uppercase;">
                    <div class="form-hint">Singkatan unik, tidak bisa diubah setelah dibuat.</div>
                    @error('singkatan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span style="color:red;">*</span></label>
                    <select name="status" class="form-control">
                        <option value="aktif"     {{ old('status', 'aktif') === 'aktif'     ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif"  {{ old('status') === 'nonaktif'            ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap Organisasi</label>
                <input type="text" name="nama_organisasi" class="form-control"
                       value="{{ old('nama_organisasi') }}"
                       placeholder="Contoh: Kumpulan Anak Seni">
                @error('nama_organisasi') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kontak</label>
                <input type="text" name="kontak" class="form-control"
                       value="{{ old('kontak') }}"
                       placeholder="Contoh: 08123456789">
                @error('kontak') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Deskripsi singkat tentang ormawa ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Ormawa
                </button>
                <a href="{{ route('admin.ormawa.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>

    </div>
</div>

@endsection