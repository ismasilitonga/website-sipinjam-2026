<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('check_in')
            ->whereNotNull('waktu_checkin')
            ->whereNull('kunci_diambil_pamdal_at')
            ->update(['kunci_diambil_pamdal_at' => DB::raw('waktu_checkin')]);

        DB::table('check_in')
            ->whereNotNull('waktu_checkout')
            ->whereNull('kunci_dikembalikan_pamdal_at')
            ->update(['kunci_dikembalikan_pamdal_at' => DB::raw('waktu_checkout')]);
    }

    public function down(): void
    {
      
        DB::table('check_in')->update([
            'kunci_diambil_pamdal_at' => null,
            'kunci_dikembalikan_pamdal_at' => null,
        ]);
    }
};