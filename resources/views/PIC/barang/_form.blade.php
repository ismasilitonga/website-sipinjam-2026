@extends('layouts.pic')

@section('title', isset($barang) ? 'Edit Barang' : 'Tambah Barang')
@section('subtitle', isset($barang) ? 'Perbarui data barang: '.$barang->nama : 'Tambahkan data barang baru ke inventaris')

@section('topbar-action')
    <a href="{{ route('pic.barang.index') }}" class="btn btn-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
@endsection

@section('content')

<div class="card" style="max-width:680px;">
    <div class="card-header">
        <span class="card-title">{{ isset($barang) ? 'Form Edit Barang' : 'Form Tambah Barang' }}</span>
    </div>
    <div class="card-body">

        @if(isset($barang))
            <form method="POST" action="{{ route('pic.barang.update', $barang->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
        @else
            <form method="POST" action="{{ route('pic.barang.store') }}" enctype="multipart/form-data">
            @csrf
        @endif

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Barang <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $barang->nama ?? '') }}"
                           placeholder="Contoh: Kursi Lipat" required>
                    @error('nama') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Barang <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="kode" class="form-control"
                           value="{{ old('kode', $barang->kode ?? '') }}"
                           placeholder="Contoh: SC-001" required>
                    @error('kode') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-hint" style="margin-top:-8px;margin-bottom:16px;">
                Gunakan awalan <strong>SC-</strong> untuk barang sumber PIC (mis. SC-001). Selain itu akan dianggap barang Ormawa.
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control"
                           value="{{ old('kategori', $barang->kategori ?? '') }}"
                           placeholder="Contoh: Elektronik">
                    @error('kategori') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Stok <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="stok" class="form-control" min="0"
                           value="{{ old('stok', $barang->stok ?? 0) }}" required>
                    @error('stok') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="satuan" class="form-control"
                           value="{{ old('satuan', $barang->satuan ?? '') }}"
                           placeholder="pcs, unit, buah, dll" required>
                    @error('satuan') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ruangan <span style="color:var(--danger)">*</span></label>
                <select name="ruangan_id" class="form-select" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($ruangans as $r)
                        <option value="{{ $r->id }}"
                            {{ old('ruangan_id', $barang->ruangan_id ?? '') == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
                @error('ruangan_id') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">Hanya menampilkan ruangan di lantai Anda.</div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Kondisi <span style="color:var(--danger)">*</span></label>
                    @php $kondisiNow = old('kondisi', $barang->kondisi ?? 'baik'); @endphp
                    <select name="kondisi" class="form-select" required>
                        <option value="baik" {{ $kondisiNow == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ $kondisiNow == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ $kondisiNow == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('kondisi') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Barang</label>
                    @php $jenisNow = old('jenis_barang', $barang->jenis_barang ?? 'bisa_dipinjam'); @endphp
                    <select name="jenis_barang" class="form-select">
                        <option value="bisa_dipinjam" {{ $jenisNow == 'bisa_dipinjam' ? 'selected' : '' }}>Bisa Dipinjam</option>
                        <option value="arsip" {{ $jenisNow == 'arsip' ? 'selected' : '' }}>Arsip</option>
                    </select>
                    @error('jenis_barang') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
                @error('deskripsi') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Foto</label>
                @if(isset($barang) && $barang->foto)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('storage/'.$barang->foto) }}" class="foto-preview" alt="Foto barang">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                @error('foto') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">Format: JPG, PNG, WEBP. Maks 2 MB.{{ isset($barang) && $barang->foto ? ' Kosongkan jika tidak ingin mengganti foto.' : '' }}</div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit"
        class="btn btn-primary"
        onclick="this.disabled=true; this.innerText='Menyimpan...'; this.form.submit();">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($barang) ? 'Simpan Perubahan' : 'Simpan' }}
                </button>
                <a href="{{ route('pic.barang.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection