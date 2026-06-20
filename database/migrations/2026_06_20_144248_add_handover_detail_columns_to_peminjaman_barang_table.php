<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->string('foto_serah')->nullable()->after('foto');
            $table->string('foto_kembali')->nullable()->after('foto_serah');
            $table->enum('kondisi_barang', ['baik', 'rusak_ringan', 'rusak_berat'])
                  ->nullable()->after('foto_kembali');
            $table->text('catatan_kondisi')->nullable()->after('kondisi_barang');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->dropColumn(['foto_serah', 'foto_kembali', 'kondisi_barang', 'catatan_kondisi']);
        });
    }
};