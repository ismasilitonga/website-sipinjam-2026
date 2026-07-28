@extends('layouts.admin')

@section('title', 'Kelola Barang')
@section('subtitle', 'Tambah, edit, dan hapus data inventaris barang')

@section('topbar-action')
    <a href="{{ route('admin.barang.create') }}" class="btn btn-primary">
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

    <div style="padding:0 16px 12px;font-size:12px;color:var(--text-muted);">
        Menampilkan seluruh inventaris barang untuk keperluan pengawasan — termasuk barang berstatus
        <strong>Arsip</strong> dan barang milik PIC/Ormawa mana pun.
    </div>

    <form method="GET" action="{{ route('admin.barang.index') }}"
          style="padding:0 16px 16px;display:flex;gap:10px;flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search ?? '' }}"
               placeholder="Cari nama barang, kode, kategori..."
               class="form-control" style="flex:1;min-width:200px;">

        <select name="sumber" class="form-select" style="width:150px;" onchange="this.form.submit()">
            <option value="">Semua Sumber</option>
            <option value="pic"    {{ ($sumber ?? '') == 'pic'    ? 'selected' : '' }}>PIC</option>
            <option value="ormawa" {{ ($sumber ?? '') == 'ormawa' ? 'selected' : '' }}>Ormawa</option>
        </select>

        <select name="jenis" class="form-select" style="width:160px;" onchange="this.form.submit()">
            <option value="">Semua Jenis</option>
            <option value="bisa_dipinjam" {{ ($jenis ?? '') == 'bisa_dipinjam' ? 'selected' : '' }}>Bisa Dipinjam</option>
            <option value="arsip"         {{ ($jenis ?? '') == 'arsip'         ? 'selected' : '' }}>Arsip</option>
        </select>

        <select name="organisasi" class="form-select" style="width:180px;" onchange="this.form.submit()">
            <option value="">Semua Ormawa</option>
            @foreach($daftarOrganisasi ?? [] as $org)
                <option value="{{ $org }}" {{ ($organisasi ?? '') == $org ? 'selected' : '' }}>{{ $org }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Cari</button>
        @if(!empty($search) || !empty($sumber) || !empty($jenis) || !empty($organisasi))
            <a href="{{ route('admin.barang.index') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>

    <div class="table-wrap">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:80px;">Foto</th>
                    <th>Nama Barang</th>
                    <th style="width:130px;">Kode</th>
                    <th style="width:130px;">Kategori</th>
                    <th style="width:80px;">Stok</th>
                    <th style="width:80px;">Satuan</th>
                    <th style="width:110px;">Kondisi</th>
                    <th style="width:140px;">Ruangan</th>
                    <th style="width:120px;">Sumber</th>
                    <th style="width:110px;">Jenis</th>
                    <th style="width:140px;">Aksi</th>
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
                            <img src="{{ asset('storage/' . $b->foto) }}" alt="{{ $b->nama }}"
                                 style="width:44px;height:44px;object-fit:cover;border-radius:8px;">
                        @else
                            <div style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <svg fill="none" stroke="#94a3b8" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td style="font-size:13px;">{{ $b->nama }}</td>
                    <td style="font-family:monospace;font-size:12.5px;">{{ $b->kode }}</td>
                    <td style="font-size:12.5px;">{{ $b->kategori ?? '—' }}</td>
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
                    <td style="font-size:12.5px;">{{ $b->ruangan->nama_ruangan ?? '—' }}</td>
                    <td>
                        @if(($b->sumber ?? null) === 'admin')
                            <span class="badge badge-cyan">Admin</span>
                        @elseif(!empty($b->organisasi))
                            <span class="badge badge-purple">{{ $b->organisasi }}</span>
                        @else
                            <span class="badge badge-blue">PIC</span>
                        @endif
                    </td>
                    <td>
                        @if(($b->jenis_barang ?? 'bisa_dipinjam') === 'arsip')
                            <span class="badge badge-gray">Arsip</span>
                        @else
                            <span class="badge badge-green">Bisa Dipinjam</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.barang.edit', $b->id) }}" class="btn btn-outline btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm"
                                    onclick="openDeleteModal('{{ $b->id }}', '{{ addslashes($b->nama) }}')">
                                Hapus
                            </button>
                            <form id="delete-form-{{ $b->id }}" method="POST"
                                  action="{{ route('admin.barang.destroy', $b->id) }}" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
                            </svg>
                            <p>Tidak ada barang yang cocok dengan filter ini.</p>
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

<div id="deleteModalOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:200;align-items:center;justify-content:center;">
    <div class="card" style="max-width:380px;width:90%;">
        <div class="card-body" style="text-align:center;padding:24px 20px;">
            <div style="width:44px;height:44px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <svg fill="none" stroke="#dc2626" viewBox="0 0 24 24" style="width:22px;height:22px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div style="font-family:'Outfit',sans-serif;font-size:15px;font-weight:600;margin-bottom:6px;">
                Hapus barang ini?
            </div>
            <div style="font-size:12.5px;color:var(--text-muted);margin-bottom:20px;">
                <span id="deleteModalNama" style="font-weight:600;color:var(--text);"></span>
                akan dihapus permanen dan tidak dapat dikembalikan.
            </div>
            <div style="display:flex;gap:10px;">
                <button type="button" class="btn btn-outline" style="flex:1;justify-content:center;" onclick="closeDeleteModal()">Batal</button>
                <button type="button" class="btn btn-danger" style="flex:1;justify-content:center;" onclick="confirmDelete()">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteFormId = null;

    function openDeleteModal(id, nama) {
        deleteFormId = id;
        document.getElementById('deleteModalNama').textContent = nama;
        document.getElementById('deleteModalOverlay').style.display = 'flex';
    }

    function closeDeleteModal() {
        deleteFormId = null;
        document.getElementById('deleteModalOverlay').style.display = 'none';
    }

    function confirmDelete() {
        if (deleteFormId) {
            document.getElementById('delete-form-' + deleteFormId).submit();
        }
    }

    document.getElementById('deleteModalOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
</script>
@endsection