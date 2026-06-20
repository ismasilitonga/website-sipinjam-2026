@php
    $layout = match(auth()->user()->role) {
        'ketua' => 'layouts.ketua',
        'anggota' => 'layouts.anggota',
        'pamdal' => 'layouts.pamdal',
        'pic' => 'layouts.pic',
        'admin' => 'layouts.admin'
    };

    $routePrefix = auth()->user()->role;
@endphp

@extends($layout)

@section('title', 'Daftar Barang')
@section('subtitle', 'Katalog barang yang tersedia untuk dipinjam')

@section('topbar-action')
    @if(auth()->user()->role === 'anggota')
    <a href="{{ route('anggota.pengajuan-barang') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajukan Pinjam Barang
    </a>
    @endif
@endsection

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:15px;">
    @forelse($barangs as $barang)
    <div style="background:#fff;border-radius:14px;border:1px solid var(--border);
                box-shadow:var(--shadow);overflow:hidden;
                transition:transform .15s,box-shadow .15s;"
         onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.09)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">

        <div style="width:100%;height:140px;
            background:#f8fafc;
            display:flex;align-items:center;justify-content:center;overflow:hidden;padding:1px;">
        @if($barang->foto)
            <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}"
             style="width:100%;height:100%;object-fit:contain;">
        @else
            <svg fill="none" stroke="#86efac" viewBox="0 0 24 24" style="width:48px;height:48px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
            </svg>
        @endif
        </div>

        <div style="padding:14px 16px;">
        <div style="font-family:'Sora',sans-serif;font-size:14px;font-weight:700;margin-bottom:4px;">
        {{ $barang->nama }}
        </div>

    @if(!empty($barang->organisasi))
        <div style="font-size:11px;color:#6b7280;margin-bottom:6px;font-weight:600;">
            {{ $barang->organisasi }}
        </div>
    @endif

    <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">
        {{ $barang->kategori ?? 'Umum' }}
        @isset($barang->satuan) · {{ $barang->satuan }} @endisset
    </div>

    @php
        $stok = $barang->stok ?? 0;
        $stokCls = $stok > 5 ? 'badge-green' : ($stok > 0 ? 'badge-orange' : 'badge-red');
        $stokLbl = $stok > 0 ? 'Stok: '.$stok : 'Habis';
    @endphp

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <span class="badge {{ $stokCls }}">{{ $stokLbl }}</span>

        @isset($barang->kondisi)
            <span style="font-size:11.5px;color:var(--text-muted);">
                {{ ucfirst($barang->kondisi) }}
            </span>
        @endisset
    </div>

        @if(auth()->user()->role === 'anggota' && $stok > 0)
        <a href="{{ route('anggota.pengajuan-barang') }}?barang_id={{ $barang->id }}"
           class="btn btn-primary btn-sm"
           style="width:100%;justify-content:center;">
            Ajukan Pinjam
        </a>
    @elseif($stok === 0)
        <button class="btn btn-outline btn-sm"
                style="width:100%;justify-content:center;opacity:.5;"
                disabled>
            Stok Habis
        </button>
    @endif
</div>
</div>

@empty
    <div style="grid-column:1/-1;">
        <div class="card">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V7"/>
                </svg>
                <p>Belum ada barang yang terdaftar.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($barangs->hasPages())
<div class="card" style="margin-top:16px;">
    <div class="pagination-wrap">{{ $barangs->links('layouts.pagination') }}</div>
</div>
@endif

@endsection
