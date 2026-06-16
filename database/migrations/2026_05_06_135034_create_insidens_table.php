<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('insidens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->string('foto')->nullable();

            $table->enum('status', ['dilaporkan', 'ditindaklanjuti', 'selesai'])
                  ->default('dilaporkan');

            $table->text('tindak_lanjut')->nullable();
            $table->foreignId('ditindak_oleh')->nullable()->constrained('users');
            $table->timestamp('waktu_ditindak')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insidens');
    }
};
