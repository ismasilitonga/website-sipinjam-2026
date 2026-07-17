<?php

namespace App\Console\Commands;

use App\Models\PeminjamanRuangan;
use Illuminate\Console\Command;

class TandaiPeminjamanSelesai extends Command
{

    protected $signature = 'peminjaman:tandai-selesai';

    protected $description = 'Menandai peminjaman ruangan yang sudah lewat tanggal_selesai sebagai "selesai" secara otomatis.';

    public function handle(): int
    {
        $jumlah = PeminjamanRuangan::whereIn('status', ['disetujui', 'berjalan'])
            ->where('tanggal_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $this->info("Berhasil menandai {$jumlah} peminjaman sebagai selesai.");

        return self::SUCCESS;
    }
}