<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            // Menandai status pemakaian fisik ruangan: booked (belum check-in),
            // in_use (sudah check-in), returned (sudah check-out), dst.
            // Dipakai untuk fitur check-in di halaman riwayat anggota.
            $table->string('status_pemakaian')->nullable()->default('booked')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangan', function (Blueprint $table) {
            $table->dropColumn('status_pemakaian');
        });
    }
};