<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::rename('ruangans', 'ruangan');
    Schema::rename('peminjaman_ruangans', 'peminjaman_ruangan');
    Schema::rename('check_ins', 'check_in');
    Schema::rename('barangs', 'barang');
    Schema::rename('peminjaman_barangs', 'peminjaman_barang');
    Schema::rename('pengalihan_barangs', 'pengalihan_barang');
    Schema::rename('insidens', 'insiden');
    Schema::rename('ormawas', 'ormawa');
}

public function down(): void
{
    Schema::rename('ruangan', 'ruangans');
    Schema::rename('peminjaman_ruangan', 'peminjaman_ruangans');
    Schema::rename('check_in', 'check_ins');
    Schema::rename('barang', 'barangs');
    Schema::rename('peminjaman_barang', 'peminjaman_barangs');
    Schema::rename('pengalihan_barang', 'pengalihan_barangs');
    Schema::rename('insiden', 'insidens');
    Schema::rename('ormawa', 'ormawas');
}
};