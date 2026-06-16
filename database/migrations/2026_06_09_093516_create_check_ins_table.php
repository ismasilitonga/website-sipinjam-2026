<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('check_ins', function (Blueprint $table) {
        $table->id();
        $table->foreignId('peminjaman_id')
              ->constrained('peminjaman_ruangans')
              ->cascadeOnDelete();

        $table->string('foto_ktp')->nullable();
        $table->timestamp('waktu_checkin')->nullable();
        $table->timestamp('waktu_checkout')->nullable();
        $table->enum('status_kunci', [
            'belum_diambil', 'diambil', 'dikembalikan'
        ])->default('belum_diambil');

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('check_ins');
}
};