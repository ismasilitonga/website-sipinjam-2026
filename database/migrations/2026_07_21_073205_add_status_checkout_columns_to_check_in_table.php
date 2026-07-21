<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            if (!Schema::hasColumn('check_in', 'waktu_checkout')) {
                $table->timestamp('waktu_checkout')->nullable()->after('kunci_dikembalikan_pamdal_at');
            }

            if (!Schema::hasColumn('check_in', 'status_checkout')) {
                $table->string('status_checkout')->nullable()->after('waktu_checkout');
            }

            if (!Schema::hasColumn('check_in', 'alasan_checkout_ditolak')) {
                $table->string('alasan_checkout_ditolak')->nullable()->after('status_checkout');
            }
        });
    }

    public function down(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            $columns = ['waktu_checkout', 'status_checkout', 'alasan_checkout_ditolak'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('check_in', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};