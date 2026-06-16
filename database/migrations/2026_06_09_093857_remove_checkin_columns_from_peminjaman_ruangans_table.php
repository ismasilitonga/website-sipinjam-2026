<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('peminjaman_ruangans', function (Blueprint $table) {
        $table->dropColumn([
            'foto_ktp',
            'waktu_checkin',
            'waktu_checkout',
            'waktu_kunci_diambil',
            'waktu_kunci_dikembalikan',
            'status_pemakaian',
        ]);
    });
}

public function down(): void
{
    Schema::table('peminjaman_ruangans', function (Blueprint $table) {
        $table->string('foto_ktp')->nullable();
        $table->timestamp('waktu_checkin')->nullable();
        $table->timestamp('waktu_checkout')->nullable();
        $table->timestamp('waktu_kunci_diambil')->nullable();
        $table->timestamp('waktu_kunci_dikembalikan')->nullable();
        $table->enum('status_pemakaian', ['booked','ongoing','completed'])->default('booked');
    });
}
};