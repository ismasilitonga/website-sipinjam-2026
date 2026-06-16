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
    <div class="table-wrap">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:100px;">Foto</th>
                    <th>Nama Barang</th>
                    <th style="width:180px;">Kode</th>
                    <th style="width:160px;">Kategori</th>
                    <th style="width:110px;">Stok</th>
                    <th style="width:110px;">Satuan</th>
                    <th style="width:130px;">Kondisi</th>
                    <th style="width:150px;">Aksi</th>
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
                    <td style="font-weight:600;font-size:13px;">{{ $b->nama }}</td>
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
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.barang.edit', $b->id) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.barang.destroy', $b->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus barang {{ addslashes($b->nama) }}?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
                            </svg>
                            <p>Belum ada barang. <a href="{{ route('admin.barang.create') }}" style="color:var(--accent);">Tambah sekarang →</a></p>
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
@endsection