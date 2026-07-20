<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['menunggu', 'diterima', 'ditolak'])
                ->nullable()
                ->after('foto_ktp');
            $table->string('alasan_verifikasi_ditolak')->nullable()->after('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'alasan_verifikasi_ditolak']);
        });
    }
};