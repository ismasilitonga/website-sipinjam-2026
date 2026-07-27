@extends('layouts.pic')

@section('title', 'Edit Barang')
@section('subtitle', 'Perbarui data barang: ' . $barang->nama)

@section('topbar-action')
    <a href="{{ route('pic.barang.index') }}" class="btn btn-outline">
        &larr; Kembali
    </a>
@endsection

@section('content')
    @include('pic.barang._form', ['barang' => $barang])
@endsection