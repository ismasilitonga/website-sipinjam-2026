<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            $table->string('ditolak_oleh')->nullable()->after('alasan_tolak');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            $table->dropColumn('ditolak_oleh');
        });
    }
};