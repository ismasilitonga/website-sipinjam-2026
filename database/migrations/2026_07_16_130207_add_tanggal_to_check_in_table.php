<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('peminjaman_id');
        });
    }

    public function down(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};