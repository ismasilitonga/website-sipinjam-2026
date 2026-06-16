@extends('layouts.pic')

@section('title', 'Kelola Barang')
@section('subtitle', 'Tambah, edit, dan hapus data inventaris barang')

@section('topbar-action')
    <a href="{{ route('pic.barang.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Barang
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
    <span class="card-title">Daftar Barang</span>
    <span class="badge badge-gray">{{ $barang->total() }} barang</span>
</div>

<form method="GET"
      action="{{ route('pic.barang.index') }}"
      style="
        padding:16px;
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        align-items:center;
        border-bottom:1px solid #e5e7eb;">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari nama barang, kode, kategori..."
        style="
            flex:1;
            min-width:220px;
            padding:8px 12px;
            border:1px solid #d1d5db;
            border-radius:8px;">

    <select
        name="sumber"
        style="
            padding:8px 12px;
            border:1px solid #d1d5db;
            border-radius:8px;">

        <option value="">Semua Sumber</option>

        <option value="pic"
            {{ request('sumber') == 'pic' ? 'selected' : '' }}>
            PIC
        </option>

        <option value="ormawa"
            {{ request('sumber') == 'ormawa' ? 'selected' : '' }}>
            Ormawa
        </option>

    </select>

    <button type="submit" class="btn btn-primary">
        Cari
    </button>

    @if(request('search') || request('sumber'))
        <a href="{{ route('pic.barang.index') }}"
           class="btn btn-outline">
            Reset
        </a>
    @endif
</form>

<div class="table-wrap" style="overflow-x:auto;">
    <table style="width:100%;">
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th style="width:60px;">Foto</th>
                <th style="width:150px;">Nama Barang</th>
                <th style="width:130px;">Kode</th>
                <th style="width:120px;">Kategori</th>
                <th style="width:120px;">Sumber</th>
                <th style="width:80px;">Stok</th>
                <th style="width:80px;">Satuan</th>
                <th style="width:130px;">Kondisi</th>
                <th style="width:120px;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($barang as $b)
            <tr>
                <td style="color:var(--text-muted);font-size:12px;">
                    {{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}
                </td>

                <td>
                    @if($b->foto)
                        <img src="{{ asset('storage/' . $b->foto) }}"
                             alt="{{ $b->nama }}"
                             style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                    @else
                        <div style="width:48px;height:48px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <svg fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="width:20px;height:20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </td>

                <td style="font-weight:600;font-size:13px;">
                    {{ $b->nama }}
                </td>

                <td style="font-family:monospace;font-size:12.5px;">
                    {{ $b->kode }}
                </td>

                <td style="font-size:12.5px;">
                    {{ $b->kategori ?? '—' }}
                </td>

                <td>
                    @if($b->organisasi)
                        <span class="badge badge-purple">
                            {{ $b->organisasi }}
                        </span>
                    @else
                        <span class="badge badge-blue">
                            PIC
                        </span>
                    @endif
                </td>

                <td>
                    @php $stok = $b->stok ?? 0; @endphp

                    <span class="badge {{ $stok > 5 ? 'badge-green' : ($stok > 0 ? 'badge-orange' : 'badge-red') }}">
                        {{ $stok }}
                    </span>
                </td>

                <td style="font-size:12.5px;">
                    {{ $b->satuan }}
                </td>

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

                    <span class="badge {{ $kCls }}">
                        {{ $kLbl }}
                    </span>
                </td>

                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('pic.barang.edit', $b->id) }}"
                           class="btn btn-outline btn-sm">
                            Edit
                        </a>
        <button type="button" class="btn btn-danger btn-sm"
            onclick="bukaModalBarang('{{ $b->id }}', '{{ addslashes($b->nama) }}')">
            Hapus
        </button>
    </div>
</td>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
                        </svg>

                        <p>
                            Belum ada barang.
                            <a href="{{ route('pic.barang.create') }}"
                               style="color:var(--accent);">
                                Tambah sekarang →
                            </a>
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

    @if($barang->hasPages())
    <div class="pagination-wrap">{{ $barang->links('layouts.pagination') }}</div>
    @endif
</div>


<div id="modalHapusBarang" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:32px 28px;width:100%;max-width:400px;
                box-shadow:0 10px 40px rgba(0,0,0,0.18);margin:16px;">

        <div style="width:52px;height:52px;border-radius:50%;background:#fee2e2;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:17px;font-weight:700;margin-bottom:6px;">Hapus Barang?</div>
            <div style="font-size:14px;color:var(--text-muted);line-height:1.5;">
                Anda akan menghapus barang <strong id="modalNamaBarang"></strong>.
                Tindakan ini <strong>permanen</strong> dan tidak dapat dibatalkan.
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button type="button" onclick="tutupModalBarang()"
                style="flex:1;padding:10px;border:1.5px solid #e5e7eb;border-radius:8px;
                       background:#fff;font-size:14px;font-weight:500;cursor:pointer;">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('formHapusBarang').submit()"
                style="flex:1;padding:10px;border:none;border-radius:8px;
                       background:#dc2626;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<form id="formHapusBarang" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
const baseUrlBarang = "{{ url('pic/barang') }}";

function bukaModalBarang(id, nama) {
    document.getElementById('modalNamaBarang').textContent = nama;
    document.getElementById('formHapusBarang').action = `${baseUrlBarang}/${id}`;
    document.getElementById('modalHapusBarang').style.display = 'flex';
}

function tutupModalBarang() {
    document.getElementById('modalHapusBarang').style.display = 'none';
}

document.getElementById('modalHapusBarang').addEventListener('click', function(e) {
    if (e.target === this) tutupModalBarang();
});
</script>

@endsection