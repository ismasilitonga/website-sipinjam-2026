@extends('layouts.ketua')

@section('title', 'Pilih Jenis Barang')
@section('subtitle', 'Tentukan jenis barang yang akan ditambahkan')

@section('content')

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Pilih Jenis Barang</h2>
    </div>
    <div class="card-body">

        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:20px;">

            <a href="{{ route('ketua.barang-ormawa.create', ['jenis' => 'pinjam']) }}"
               style="text-decoration:none; border:1.5px solid var(--border); border-radius:var(--radius);
                      padding:28px 24px; text-align:center; background:#f8fafc;
                      transition:border-color .15s, box-shadow .15s;"
               onmouseover="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 4px 16px rgba(22,163,74,.1)'"
               onmouseout="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                <div style="font-size:42px; margin-bottom:12px;">📦</div>
                <h4 style="font-family:'Sora',sans-serif; font-size:14px; font-weight:700;
                           color:var(--text); margin-bottom:8px;">Bisa Dipinjam</h4>
                <p style="font-size:13px; color:var(--text-muted); line-height:1.5;">
                    Barang akan muncul di katalog dan dapat dipinjam pengguna.
                </p>
            </a>

            <a href="{{ route('ketua.barang-ormawa.create', ['jenis' => 'arsip']) }}"
               style="text-decoration:none; border:1.5px solid var(--border); border-radius:var(--radius);
                      padding:28px 24px; text-align:center; background:#f8fafc;
                      transition:border-color .15s, box-shadow .15s;"
               onmouseover="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 4px 16px rgba(22,163,74,.1)'"
               onmouseout="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                <div style="font-size:42px; margin-bottom:12px;">🗄️</div>
                <h4 style="font-family:'Sora',sans-serif; font-size:14px; font-weight:700;
                           color:var(--text); margin-bottom:8px;">Arsip Inventaris</h4>
                <p style="font-size:13px; color:var(--text-muted); line-height:1.5;">
                    Barang hanya sebagai inventaris internal dan tidak bisa dipinjam.
                </p>
            </a>

        </div>

    </div>
</div>

@endsection