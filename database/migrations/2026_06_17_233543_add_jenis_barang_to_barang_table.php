<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('barang', function (Blueprint $table) {
        $table->enum('jenis_barang', ['bisa_dipinjam', 'arsip'])
              ->default('bisa_dipinjam')
              ->after('organisasi');
    });
}
};
