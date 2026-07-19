<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $table->timestamp('kunci_diambil_pamdal_at')->nullable()->after('waktu_checkout');
            $table->timestamp('kunci_dikembalikan_pamdal_at')->nullable()->after('kunci_diambil_pamdal_at');
        });
    }

    public function down(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $table->dropColumn(['kunci_diambil_pamdal_at', 'kunci_dikembalikan_pamdal_at']);
        });
    }
};