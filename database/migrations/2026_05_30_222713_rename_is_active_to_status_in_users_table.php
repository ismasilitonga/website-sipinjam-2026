<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom status jika belum ada
        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('organisasi');
            });
        }

        // Drop is_active jika masih ada (migrasi lama)
        if (Schema::hasColumn('users', 'is_active')) {
            DB::table('users')->where('is_active', 1)->update(['status' => 'aktif']);
            DB::table('users')->where('is_active', 0)->update(['status' => 'pending']);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(false)->after('organisasi');
            });

            DB::table('users')->where('status', 'aktif')->update(['is_active' => 1]);
            DB::table('users')->where('status', 'pending')->update(['is_active' => 0]);
            DB::table('users')->where('status', 'ditolak')->update(['is_active' => 0]);
        }

        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};