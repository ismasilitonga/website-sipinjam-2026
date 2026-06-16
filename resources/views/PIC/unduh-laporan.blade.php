@extends('layouts.pic')

@section('title', 'Unduh Laporan')
@section('subtitle', 'Unduh laporan peminjaman ruangan dan barang')

@section('content')

<div class="card" style="margin-bottom:20px;">
    <div style="padding:20px;">
         <p style="color:#000;margin-bottom:20px;">
            Pilih Jenis File Laporan Ruangan yang ingin diunduh.
        </p>

        <p style="font-weight:700;margin-bottom:12px;">
            🏢 Laporan Ruangan
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="{{ route('pic.laporan.pdf') }}" class="btn btn-primary">
                📄 PDF Ruangan
            </a>

            <a href="{{ route('pic.laporan.excel') }}"
               class="btn"
               style="background:#163b72;color:white;">
                📊 Excel Ruangan
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div style="padding:20px;">
        <p style="color:#000;margin-bottom:20px;">
            Pilih Jenis File Laporan Barang yang ingin diunduh.
        </p>

        <p style="font-weight:700;margin-bottom:12px;">
            📦 Laporan Barang
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="{{ route('pic.laporan.pdf-barang') }}" class="btn btn-primary">
                📄 PDF Barang
            </a>

            <a href="{{ route('pic.laporan.excel-barang') }}"
               class="btn"
               style="background:#163b72;color:white;">
                📊 Excel Barang
            </a>
        </div>
    </div>
</div>

@endsection