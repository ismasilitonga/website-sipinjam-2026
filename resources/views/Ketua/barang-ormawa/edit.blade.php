@extends('layouts.ketua')

@section('content')
<div class="card" style="max-width:680px;">
    <div class="card-header">
        <span class="card-title">Edit Barang</span>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('ketua.barang-ormawa.update', $barang->id) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('ketua.barang-ormawa._form')
        </form>
    </div>
</div>
@endsection