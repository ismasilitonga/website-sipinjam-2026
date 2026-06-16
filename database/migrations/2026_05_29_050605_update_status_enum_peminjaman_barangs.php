<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE peminjaman_barangs 
            SET status = 'menunggu_pic' 
            WHERE status = 'menunggu_ketua'");

        DB::statement("ALTER TABLE peminjaman_barangs 
            MODIFY COLUMN status ENUM('menunggu_pic','disetujui','ditolak') 
            DEFAULT 'menunggu_pic'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman_barangs 
            MODIFY COLUMN status ENUM('menunggu_ketua','menunggu_pic','disetujui','ditolak') 
            DEFAULT 'menunggu_ketua'");
    }
};