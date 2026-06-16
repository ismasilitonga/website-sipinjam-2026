<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjaman_ruangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ruangan_id')->constrained()->cascadeOnDelete();
            $table->string('nama_ormawa');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->text('keperluan');

$table->enum('status', [
    'menunggu_ketua',
    'menunggu_pic',
    'disetujui',
    'ditolak',
    'berjalan',
    'selesai',  
])->default('menunggu_ketua');

            $table->string('alasan_tolak')->nullable();

            $table->enum('status_pemakaian', ['booked', 'ongoing', 'completed'])->default('booked');

            $table->timestamp('waktu_checkin')->nullable();
            $table->timestamp('waktu_checkout')->nullable();

            $table->timestamp('waktu_kunci_diambil')->nullable();
            $table->timestamp('waktu_kunci_dikembalikan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_ruangans');
    }
};
