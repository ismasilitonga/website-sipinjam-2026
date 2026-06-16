<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
   
    <div class="form-group">
        <label class="form-label">Nama Barang <span style="color:var(--danger)">*</span></label>
        <input type="text" name="nama" class="form-control" 
               value="{{ old('nama', $barang->nama ?? '') }}" required>
        @error('nama') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Kategori <span style="color:var(--danger)">*</span></label>
        <input type="text" name="kategori" class="form-control" list="list-kategori"
               value="{{ old('kategori', $barang->kategori ?? '') }}" required>
        <datalist id="list-kategori">
            @foreach($kategoris as $kat)
                <option value="{{ $kat }}">
            @endforeach
        </datalist>
        @error('kategori') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-top:20px;">

    <div class="form-group">
        <label class="form-label">Stok <span style="color:var(--danger)">*</span></label>
        <input type="number" name="stok" class="form-control" min="0"
               value="{{ old('stok', $barang->stok ?? 0) }}" required>
        @error('stok') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Satuan <span style="color:var(--danger)">*</span></label>
        <input type="text" name="satuan" class="form-control" 
               value="{{ old('satuan', $barang->satuan ?? '') }}" required placeholder="Contoh: unit, set">
        @error('satuan') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Kondisi <span style="color:var(--danger)">*</span></label>
        <select name="kondisi" class="form-select" required>
            @foreach(['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'] as $val => $label)
                <option value="{{ $val }}" {{ old('kondisi', $barang->kondisi ?? '') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('kondisi') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group" style="margin-top:20px;">
    <label class="form-label">Foto Barang</label>
    @if(isset($barang) && $barang->foto)
        <div style="margin-bottom:10px;">
            <img src="{{ asset('storage/'.$barang->foto) }}" style="width:100px;border-radius:8px;border:1px solid #ddd;">
        </div>
    @endif
    <input type="file" name="foto" class="form-control" accept="image/*">
    @error('foto') <div class="form-error">{{ $message }}</div> @enderror
    <div class="form-hint" style="font-size:12px;color:#64748b;margin-top:4px;">
        Format: JPG, PNG. Maks 2MB. {{ isset($barang) && $barang->foto ? 'Kosongkan jika tidak ingin mengganti.' : '' }}
    </div>
</div>

<div class="form-group" style="margin-top:20px;">
    <label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
    @error('deskripsi') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div style="margin-top:24px;display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary" onclick="this.disabled=true; this.innerText='Menyimpan...'; this.form.submit();">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;margin-right:6px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ isset($barang) ? 'Simpan Perubahan' : 'Tambah Barang' }}
    </button>
<a href="{{ route('ketua.barang-ormawa.index') }}" class="btn btn-outline">Batal</a>
</div>