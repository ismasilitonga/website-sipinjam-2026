<?php

namespace App\Notifications\Concerns;

use App\Models\PeminjamanRuangan;
use Carbon\Carbon;

trait FormatsTanggalPeminjaman
{
 
    protected function formatTanggalPeminjaman(PeminjamanRuangan $peminjaman): string
    {
        $mulai   = Carbon::parse($peminjaman->tanggal_mulai);
        $selesai = Carbon::parse($peminjaman->tanggal_selesai);

        if ($mulai->isSameDay($selesai)) {
            return $mulai->translatedFormat('d F Y') . ', ' .
                   $mulai->format('H:i') . '–' . $selesai->format('H:i');
        }

        return $mulai->translatedFormat('d F Y') . ' – ' . $selesai->translatedFormat('d F Y') .
               ', setiap hari jam ' . $mulai->format('H:i') . '–' . $selesai->format('H:i');
    }
}