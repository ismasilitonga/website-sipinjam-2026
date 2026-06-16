@extends('layouts.admin')

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
            <a href="{{ route('admin.laporan.pdf') }}" class="btn btn-primary">
                📄 PDF Ruangan
            </a>

            <a href="{{ route('admin.laporan.excel') }}"
               class="btn"
               style="background:#163b72;color:white;">
                📊 Excel Ruangan
            </a>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div style="padding:20px;">
        <p style="color:#000;margin-bottom:20px;">
            Pilih Jenis File Laporan Barang yang ingin diunduh.
        </p>

        <p style="font-weight:700;margin-bottom:12px;">
            📦 Laporan Barang
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="{{ route('admin.laporan.pdf-barang') }}" class="btn btn-primary">
                📄 PDF Barang
            </a>

            <a href="{{ route('admin.laporan.excel-barang') }}"
               class="btn"
               style="background:#163b72;color:white;">
                📊 Excel Barang
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div style="padding:20px;">
        <p style="color:#000;margin-bottom:20px;">
            Pilih Jenis File Laporan Insiden yang ingin diunduh.
        </p>

        <p style="font-weight:700;margin-bottom:12px;">
            ⚠️ Laporan Insiden
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <a href="{{ route('admin.laporan-insiden.pdf') }}"
               class="btn btn-primary">
                📄 PDF Insiden
            </a>

            <a href="{{ route('admin.laporan-insiden.excel') }}"
               class="btn"
               style="background:#163b72;color:white;">
                📊 Excel Insiden
            </a>
        </div>
    </div>
</div>
@endsection