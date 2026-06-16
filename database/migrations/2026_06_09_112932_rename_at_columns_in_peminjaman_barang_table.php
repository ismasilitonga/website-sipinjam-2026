<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('peminjaman_barang', function (Blueprint $table) {
        $table->renameColumn('waktu_diserahkan', 'waktu_diserahkan');
        $table->renameColumn('waktu_diterima_kembali', 'waktu_diterima_kembali');
    });
}

public function down(): void
{
    Schema::table('peminjaman_barang', function (Blueprint $table) {
        $table->renameColumn('waktu_diserahkan', 'waktu_diserahkan');
        $table->renameColumn('waktu_diterima_kembali', 'waktu_diterima_kembali');
    });
}
};