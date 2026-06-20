@extends('layouts.pic')

@section('title', isset($ruangan) ? 'Edit Ruangan' : 'Tambah Ruangan')
@section('subtitle', isset($ruangan) ? 'Perbarui data ruangan: '.$ruangan->nama : 'Daftarkan ruangan baru ke sistem')

@section('topbar-action')
    <a href="{{ route('pic.ruangan.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="card" style="max-width:680px;">
    <div class="card-header">
        <span class="card-title">{{ isset($ruangan) ? 'Form Edit Ruangan' : 'Form Ruangan Baru' }}</span>
    </div>
    <div class="card-body">

        @if(isset($ruangan))
            <form method="POST" action="{{ route('pic.ruangan.update', $ruangan->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
        @else
            <form method="POST" action="{{ route('pic.ruangan.store') }}" enctype="multipart/form-data">
            @csrf
        @endif

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Ruangan <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" class="form-control"
                    value="{{ old('nama', $ruangan->nama ?? '') }}"
                    placeholder="Contoh: Ruang Rapat A" required>
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Ruangan <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="kode" class="form-control"
                           value="{{ old('kode', $ruangan->kode ?? '') }}"
                           placeholder="Contoh: RR-A01" required>
                    @error('kode') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Gedung</label>
                    <input type="text" name="gedung" class="form-control"
                           value="{{ old('gedung', $ruangan->gedung ?? '') }}"
                           placeholder="Gedung A">
                    @error('gedung') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Lantai</label>
                    <input type="text" name="lantai" class="form-control"
                           value="{{ old('lantai', $ruangan->lantai ?? '') }}"
                           placeholder="1">
                    @error('lantai') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kapasitas (orang) <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="kapasitas" class="form-control" min="0"
                           value="{{ old('kapasitas', $ruangan->kapasitas ?? '') }}"
                           placeholder="30" required>
                    @error('kapasitas') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Fasilitas</label>
                <input type="text" name="fasilitas" class="form-control"
                       value="{{ old('fasilitas', $ruangan->fasilitas ?? '') }}"
                       placeholder="Contoh: AC, Proyektor, Whiteboard">
                @error('fasilitas') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status <span style="color:var(--danger)">*</span></label>
                
                <select name="status" class="form-select" required>
                <option value="" disabled {{ old('status', $ruangan->status ?? '') === '' ? 'selected' : '' }}>-- Pilih Status --</option>
                        <option value="tersedia" {{ old('status', $ruangan->status ?? '') === 'tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="tidak_tersedia" {{ old('status', $ruangan->status ?? '') === 'tidak_tersedia' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                @error('status') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Foto Ruangan</label>
                @if(isset($ruangan) && $ruangan->foto)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/'.$ruangan->foto) }}" class="foto-preview" alt="Foto ruangan">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                @error('foto') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">Format: JPG, PNG. Maks 2 MB.{{ isset($ruangan) && $ruangan->foto ? ' Kosongkan jika tidak ingin mengganti foto.' : '' }}</div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit"
        class="btn btn-primary"
        onclick="this.disabled=true; this.innerText='Menyimpan...'; this.form.submit();">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($ruangan) ? 'Simpan Perubahan' : 'Tambah Ruangan' }}
                </button>
                <a href="{{ route('pic.ruangan.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
