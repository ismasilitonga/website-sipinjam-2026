<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman_barang 
            MODIFY COLUMN status ENUM('menunggu_pic','disetujui','ditolak','selesai') 
            DEFAULT 'menunggu_pic'");
    }

    public function down(): void
    {
        DB::statement("UPDATE peminjaman_barang 
            SET status = 'disetujui' 
            WHERE status = 'selesai'");

        DB::statement("ALTER TABLE peminjaman_barang 
            MODIFY COLUMN status ENUM('menunggu_pic','disetujui','ditolak') 
            DEFAULT 'menunggu_pic'");
    }
};