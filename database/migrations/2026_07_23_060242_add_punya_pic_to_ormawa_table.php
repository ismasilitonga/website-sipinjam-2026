<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ormawa', function (Blueprint $table) {
            $table->boolean('punya_pic')->default(false)->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('ormawa', function (Blueprint $table) {
            $table->dropColumn('punya_pic');
        });
    }
};