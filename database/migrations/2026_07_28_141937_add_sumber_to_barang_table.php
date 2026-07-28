<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('sumber', 20)->nullable()->after('organisasi');
        });
        DB::table('barang')->whereNull('organisasi')->update(['sumber' => 'pic']);
        DB::table('barang')->whereNotNull('organisasi')->where('organisasi', '!=', '')->update(['sumber' => 'ormawa']);
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('sumber');
        });
    }
};