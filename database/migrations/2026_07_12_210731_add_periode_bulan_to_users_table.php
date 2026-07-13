<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('periode_mulai_bulan')->nullable()->after('periode_mulai');
            $table->unsignedTinyInteger('periode_selesai_bulan')->nullable()->after('periode_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['periode_mulai_bulan', 'periode_selesai_bulan']);
        });
    }
};