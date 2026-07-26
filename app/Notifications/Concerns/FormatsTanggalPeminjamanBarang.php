<?php

namespace App\Notifications\Concerns;

use App\Models\PeminjamanBarang;
use Carbon\Carbon;

trait FormatsTanggalPeminjamanBarang
{
    protected function formatTanggalPeminjamanBarang(PeminjamanBarang $peminjaman): string
    {
        $pinjam  = Carbon::parse($peminjaman->tanggal_pinjam);
        $kembali = Carbon::parse($peminjaman->tanggal_kembali_rencana);

        if ($pinjam->isSameDay($kembali)) {
            return $pinjam->translatedFormat('d F Y');
        }

        return $pinjam->translatedFormat('d F Y') . ' – ' . $kembali->translatedFormat('d F Y');
    }
}