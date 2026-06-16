<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;

class RiwayatRuanganAdminController extends Controller
{
    public function index()
    {
        $filters = [
            ''               => 'Semua',
            'menunggu_ketua' => 'Menunggu Ketua',
            'menunggu_pic'   => 'Menunggu PIC',
            'disetujui'      => 'Disetujui',
            'ditolak'        => 'Ditolak',
            'berjalan'       => 'Berjalan',
            'selesai'        => 'Selesai',
        ];

        $query = PeminjamanRuangan::with(['ruangan', 'user', 'checkIn']);

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        $riwayat = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('shared.riwayat-ruangan', compact('riwayat', 'filters'));
    }
}