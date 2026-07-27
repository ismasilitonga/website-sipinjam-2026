@extends('layouts.pic')

@section('title', 'Tambah Barang')
@section('subtitle', 'Tambahkan data barang baru ke inventaris')

@section('topbar-action')
    <a href="{{ route('pic.barang.index') }}" class="btn btn-outline">
        &larr; Kembali
    </a>
@endsection

@section('content')
    @include('pic.barang._form')
@endsection