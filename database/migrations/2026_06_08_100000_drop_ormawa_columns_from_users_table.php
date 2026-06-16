<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $columns = ['singkatan', 'kontak', 'deskripsi', 'nama_organisasi'];
        foreach ($columns as $col) {
            if (Schema::hasColumn('users', $col)) {
                $table->dropColumn($col);
            }
        }
    });
}
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback: kembalikan kolom jika migration di-revert
            $table->string('singkatan')->nullable()->after('organisasi');
            $table->string('kontak')->nullable()->after('singkatan');
            $table->text('deskripsi')->nullable()->after('kontak');
            $table->string('nama_organisasi')->nullable()->after('deskripsi');
        });
    }
};