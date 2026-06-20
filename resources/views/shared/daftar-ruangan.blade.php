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

@section('title', 'Daftar Ruangan')
@section('subtitle', 'Katalog ruangan yang tersedia untuk dipinjam')

@section('topbar-action')
    @if(auth()->user()->role === 'anggota')
        <a href="{{ route('anggota.pengajuan-ruangan') }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Peminjaman
        </a>
    @endif
@endsection

@section('content')
<div class="item-grid">
    @forelse($ruangans as $ruangan)
    <div class="item-card">
        <div class="item-card-img">
            @if($ruangan->foto)
                <img src="{{ asset('storage/' . $ruangan->foto) }}" alt="{{ $ruangan->nama_ruangan }}"
                     style="width:100%;height:100%;object-fit:cover;">
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            @endif
        </div>
        <div class="item-card-body">
            <div class="item-card-title">{{ $ruangan->nama_ruangan}}</div>
            <div class="item-card-sub">
                {{ $ruangan->gedung ?? '' }}{{ isset($ruangan->gedung, $ruangan->lantai) ? ' · Lantai '.$ruangan->lantai : '' }}
            </div>
            <div style="display:flex;justify-content:flex-end;">
    @if(isset($ruangan->kapasitas))
    <span style="font-size:11.5px;color:var(--text-muted);">
        👥 {{ $ruangan->kapasitas }}
    </span>
    @endif
</div>
            <div style="margin-top:12px;display:flex;gap:8px;">
                @if($routePrefix === 'anggota')
<a href="{{ route('anggota.pengajuan-ruangan') }}?ruangan_id={{ $ruangan->id }}"
class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">
    Ajukan
</a>
@endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;">
        <div class="card">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <p>Belum ada ruangan yang terdaftar.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($ruangans->hasPages())
<div class="card" style="margin-top:16px;">
    <div class="pagination-wrap">{{ $ruangans->links('layouts.pagination') }}</div>
</div>
@endif

@endsection