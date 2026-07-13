@extends('layouts.anggota')

@section('title', 'Edit Pengajuan')
@section('subtitle', 'Perbarui data pengajuan ruangan kamu')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <span class="card-title">Edit Pengajuan Ruangan</span>
    </div>

    <form method="POST"
          action="{{ route('anggota.riwayat-ruangan.update', $peminjaman->id) }}"
          enctype="multipart/form-data"
          style="padding:20px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Ruangan</label>
            <select name="ruangan_id" class="form-control" required>
                @foreach($ruangans as $r)
                    <option value="{{ $r->id }}" {{ $peminjaman->ruangan_id == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_ruangan }} - {{ $r->gedung }} Lt.{{ $r->lantai }}
                    </option>
                @endforeach
            </select>
            @error('ruangan_id')
                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex;gap:12px;margin-bottom:16px;">
            <div style="flex:1;">
                <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Mulai</label>
                <input type="datetime-local" name="tanggal_mulai" class="form-control"
                    value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->format('Y-m-d\TH:i') }}" required>
                @error('tanggal_mulai')
                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            <div style="flex:1;">
                <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Selesai</label>
                <input type="datetime-local" name="tanggal_selesai" class="form-control"
                    value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->format('Y-m-d\TH:i') }}" required>
                @error('tanggal_selesai')
                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Keperluan</label>
            <input type="text" name="keperluan" class="form-control"
                value="{{ $peminjaman->keperluan }}" required>
            @error('keperluan')
                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Dokumen Pendukung (opsional)</label>
            @if($peminjaman->dokumen_pendukung)
                <div style="font-size:12px;margin-bottom:6px;">
                    File saat ini:
                    <a href="{{ Storage::url($peminjaman->dokumen_pendukung) }}" target="_blank">Lihat Dokumen</a>
                </div>
            @endif
            <input type="file" name="dokumen_pendukung" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                Kosongkan jika tidak ingin mengganti file.
            </div>
            @error('dokumen_pendukung')
                <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <a href="{{ route('anggota.riwayat-ruangan') }}" class="btn btn-outline" style="padding:8px 16px;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="padding:8px 16px;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection