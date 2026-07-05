@extends('layouts.ketua')

@section('title', 'Tambah Barang')
@section('subtitle', 'Form tambah barang ormawa')

@section('content')
<div class="card" style="max-width:680px;">
    <div class="card-header">
        <span class="card-title">Tambah Barang</span>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('ketua.barang-ormawa.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_barang" value="{{ $jenis == 'arsip' ? 'arsip' : 'bisa_dipinjam' }}">

            @include('ketua.barang-ormawa._form')
        </form>
    </div>
</div>
@endsection