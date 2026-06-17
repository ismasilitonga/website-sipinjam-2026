@extends('layouts.ketua')

@section('title', 'List Barang Ormawa')
@section('subtitle', 'Kelola inventaris barang milik ormawa kamu')

@section('topbar-action')
    <a href="{{ route('ketua.barang-ormawa.pilih-jenis') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Barang Ormawa
    </a>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="background:#f0fdf4;border:0.5px solid #86efac;border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13px;color:#166534;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="background:#fef2f2;border:0.5px solid #fca5a5;border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13px;color:#991b1b;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

<div style="background:#f5f3ff;border:0.5px solid #c4b5fd;border-radius:8px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:flex-start;gap:9px;font-size:12.5px;color:#4c1d95;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>
        Kelola inventaris barang ormawa kamu di sini. Barang <strong>Bisa Dipinjam</strong>
        akan muncul di katalog dan dapat diajukan pinjam oleh anggota.
        Barang <strong>Arsip</strong> hanya tercatat sebagai inventaris internal dan tidak terlihat oleh anggota.
        Barang dari PIC hanya bisa dilihat, tidak bisa diedit.
    </span>
</div>

<div style="display:flex;justify-content:flex-end;align-items:center;margin-bottom:16px;">
    <span style="background:#f1f5f9;border:0.5px solid #e2e8f0;border-radius:8px;padding:6px 12px;font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
        </svg>
        {{ $barang->total() }} barang
    </span>
</div>

<div class="card">
    <div class="card-header" style="padding-bottom:14px;display:flex;align-items:center;justify-content:space-between;">
        <span class="card-title">Inventaris Barang Ormawa</span>
        <div style="display:flex;gap:6px;align-items:center;">
            <span style="font-size:11px;padding:3px 9px;border-radius:12px;background:#eeedfe;color:#534ab7;font-weight:500;">Milik Ormawa</span>
            <span style="font-size:11px;padding:3px 9px;border-radius:12px;background:#e6f1fb;color:#185fa5;font-weight:500;">Dari PIC</span>
        </div>
    </div>

    <form method="GET" action="{{ route('ketua.barang-ormawa.index') }}"
        style="padding:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama barang, kode, kategori..."
            style="flex:1;min-width:220px;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;">
        <select name="sumber" style="padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;">
            <option value="">Semua Sumber</option>
            <option value="ormawa" {{ request('sumber') == 'ormawa' ? 'selected' : '' }}>Ormawa</option>
            <option value="pic"    {{ request('sumber') == 'pic'    ? 'selected' : '' }}>PIC</option>
        </select>
        <select name="jenis" style="padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;">
            <option value="">Semua Jenis</option>
            <option value="bisa_dipinjam" {{ request('jenis') == 'bisa_dipinjam' ? 'selected' : '' }}>Bisa Dipinjam</option>
            <option value="arsip"         {{ request('jenis') == 'arsip'         ? 'selected' : '' }}>Arsip</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
        @if(request('search') || request('sumber') || request('jenis'))
            <a href="{{ route('ketua.barang-ormawa.index') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>

    <div class="table-wrap" style="overflow-x:auto;">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:60px;">Foto</th>
                    <th style="width:160px;">Nama Barang</th>
                    <th style="width:110px;">Kode</th>
                    <th style="width:120px;">Kategori</th>
                    <th style="width:70px;">Stok</th>
                    <th style="width:80px;">Satuan</th>
                    <th style="width:120px;">Kondisi</th>
                    <th style="width:90px;">Sumber</th>
                    <th style="width:130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $b)
                @php
                    $isOrmawa = !str_starts_with($b->kode, 'BRG-');
                @endphp
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">
                        {{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        @if($b->foto)
                            <img src="{{ asset('storage/' . $b->foto) }}" alt="{{ $b->nama }}"
                                style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                        @else
                            <div style="width:48px;height:48px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="width:20px;height:20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:600;font-size:13px;">{{ $b->nama }}</td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);">{{ $b->kode }}</td>
                    <td>
                        @if($b->kategori)
                            <span style="display:inline-block;padding:3px 9px;border-radius:12px;font-size:11.5px;font-weight:500;background:#e0f2fe;color:#0369a1;">
                                {{ ucfirst($b->kategori) }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        @php $stok = $b->stok ?? 0; @endphp
                        <span class="badge {{ $stok > 5 ? 'badge-green' : ($stok > 0 ? 'badge-orange' : 'badge-red') }}">
                            {{ $stok }}
                        </span>
                    </td>
                    <td style="font-size:12.5px;">{{ $b->satuan }}</td>
                    <td>
                        @php
                            $kCls = match($b->kondisi ?? 'baik') {
                                'baik'         => 'badge-green',
                                'rusak_ringan' => 'badge-yellow',
                                'rusak_berat'  => 'badge-red',
                                default        => 'badge-gray',
                            };
                            $kLbl = match($b->kondisi ?? 'baik') {
                                'baik'         => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat'  => 'Rusak Berat',
                                default        => ucfirst($b->kondisi ?? 'baik'),
                            };
                        @endphp
                        <span class="badge {{ $kCls }}">{{ $kLbl }}</span>
                    </td>
                    <td>
                        @if($isOrmawa)
                            <span style="font-size:11px;padding:3px 8px;border-radius:10px;background:#eeedfe;color:#534ab7;font-weight:500;">Ormawa</span>
                        @else
                            <span style="font-size:11px;padding:3px 8px;border-radius:10px;background:#e6f1fb;color:#185fa5;font-weight:500;">PIC</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            @if($isOrmawa)
                                <a href="{{ route('ketua.barang-ormawa.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmHapus('{{ addslashes($b->nama) }}', {{ $b->id }})">
                                    Hapus
                                </button>
                            @else
                                <span style="font-size:11px;color:var(--text-muted);padding:4px 8px;font-style:italic;">Hanya lihat</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
                            </svg>
                            @if($search || $sumber || $jenis)
                                <p>Tidak ada barang yang cocok dengan pencarian.</p>
                                <a href="{{ route('ketua.barang-ormawa.index') }}" style="color:var(--accent);">Tampilkan semua →</a>
                            @else
                                <p>Belum ada barang ormawa. <a href="{{ route('ketua.barang-ormawa.pilih-jenis') }}" style="color:var(--accent);">Tambah sekarang →</a></p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($barang->hasPages())
        <div class="pagination-wrap">
            {{ $barang->links('layouts.pagination') }}
        </div>
    @endif
</div>

<div id="modal-hapus" class="modal-overlay">
    <div class="modal-box" style="max-width:400px;">
        <div style="text-align:center;margin-bottom:20px;">
            <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <svg fill="none" stroke="#dc2626" viewBox="0 0 24 24" style="width:26px;height:26px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700;margin-bottom:6px;">Hapus Barang?</h3>
            <p style="font-size:13px;color:var(--text-muted);">
                Barang <strong id="modal-nama"></strong> akan dihapus permanen dan tidak bisa dikembalikan.
            </p>
        </div>
        <form method="POST" id="form-hapus">
            @csrf @method('DELETE')
            <div style="display:flex;gap:10px;justify-content:center;">
            <button type="button" onclick="tutupModal()" class="btn btn-outline" style="min-width:100px;">Batal</button>
            <button type="submit" class="btn btn-danger" style="min-width:100px;">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmHapus(nama, id) {
    document.getElementById('modal-nama').textContent = nama;
    document.getElementById('form-hapus').action = '/ketua/barang-ormawa/' + id;
    document.getElementById('modal-hapus').classList.add('open');
}
function tutupModal() {
    document.getElementById('modal-hapus').classList.remove('open');
}
document.getElementById('modal-hapus').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});
</script>
@endpush