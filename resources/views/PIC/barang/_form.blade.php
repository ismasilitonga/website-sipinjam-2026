<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">
            {{ isset($barang) ? 'Edit Barang' : 'Tambah Barang' }}
        </span>
    </div>

    <form method="POST"
          action="{{ isset($barang) ? route('pic.barang.update', $barang->id) : route('pic.barang.store') }}"
          enctype="multipart/form-data"
          style="padding:20px;display:flex;flex-direction:column;gap:16px;">

        @csrf
        @if(isset($barang))
            @method('PUT')
        @endif

        <div>
            <label>Nama Barang</label>
            <input type="text" name="nama" value="{{ old('nama', $barang->nama ?? '') }}"
                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;" required>
            @error('nama') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Kode Barang</label>
            <input type="text" name="kode" value="{{ old('kode', $barang->kode ?? '') }}"
                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;" required>
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                Gunakan awalan <strong>SC-</strong> untuk barang sumber PIC (mis. SC-001). Selain itu akan dianggap barang Ormawa.
            </div>
            @error('kode') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Kategori</label>
            <input type="text" name="kategori" value="{{ old('kategori', $barang->kategori ?? '') }}"
                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;">
            @error('kategori') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex;gap:12px;">
            <div style="flex:1;">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $barang->stok ?? 0) }}"
                       style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;" min="0" required>
                @error('stok') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
            </div>
            <div style="flex:1;">
                <label>Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan ?? '') }}"
                       placeholder="pcs, unit, buah, dll"
                       style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;" required>
                @error('satuan') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div>
            <label>Ruangan</label>
            <select name="ruangan_id"
                    style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;" required>
                <option value="">-- Pilih Ruangan --</option>
                @foreach($ruangans as $r)
                    <option value="{{ $r->id }}"
                        {{ old('ruangan_id', $barang->ruangan_id ?? '') == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_ruangan }}
                    </option>
                @endforeach
            </select>
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                Hanya menampilkan ruangan di lantai Anda.
            </div>
            @error('ruangan_id') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Kondisi</label>
            @php $kondisiNow = old('kondisi', $barang->kondisi ?? 'baik'); @endphp
            <select name="kondisi" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;" required>
                <option value="baik" {{ $kondisiNow == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak_ringan" {{ $kondisiNow == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_berat" {{ $kondisiNow == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            @error('kondisi') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Jenis Barang</label>
            @php $jenisNow = old('jenis_barang', $barang->jenis_barang ?? 'bisa_dipinjam'); @endphp
            <select name="jenis_barang" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;">
                <option value="bisa_dipinjam" {{ $jenisNow == 'bisa_dipinjam' ? 'selected' : '' }}>Bisa Dipinjam</option>
                <option value="arsip" {{ $jenisNow == 'arsip' ? 'selected' : '' }}>Arsip</option>
            </select>
        </div>

        <div>
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;resize:vertical;">{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
            @error('deskripsi') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Foto</label>
            <input type="file" name="foto" accept="image/*"
                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;">
            @error('foto') <div style="color:#dc2626;font-size:12px;">{{ $message }}</div> @enderror

            @if(isset($barang) && $barang->foto)
                <div style="margin-top:8px;">
                    <img src="{{ asset('storage/' . $barang->foto) }}"
                         style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                </div>
            @endif
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <a href="{{ route('pic.barang.index') }}" class="btn btn-outline" style="flex:1;text-align:center;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="flex:1;">
                {{ isset($barang) ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>