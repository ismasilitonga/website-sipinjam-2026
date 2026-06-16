<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjaman_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained()->cascadeOnDelete();
            $table->string('nama_ormawa');
            $table->integer('jumlah');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->text('keperluan');

            $table->enum('status', [
                'menunggu_pic',
                'disetujui',
                'ditolak',
            ])->default('menunggu_pic');

            $table->string('alasan_tolak')->nullable();

            $table->timestamp('waktu_diserahkan')->nullable();
            $table->foreignId('diserahkan_oleh')->nullable()->constrained('users');

            $table->timestamp('waktu_diterima_kembali')->nullable();
            $table->foreignId('diterima_oleh')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barangs');
    }
};
