<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('pengalihan_barang', function (Blueprint $table) {
        $table->renameColumn('waktu_dikonfirmasi', 'waktu_dikonfirmasi');
    });
}

public function down(): void
{
    Schema::table('pengalihan_barang', function (Blueprint $table) {
        $table->renameColumn('waktu_dikonfirmasi', 'waktu_dikonfirmasi');
    });
}
};
