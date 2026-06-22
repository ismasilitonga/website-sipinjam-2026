<?php

namespace App\Http\Controllers\Pamdal;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;

class DashboardPamdalController extends Controller
{
    public function index()
    {
        $peminjamansHariIni = PeminjamanRuangan::with(['user', 'ruangan'])
            ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
            ->whereDate('tanggal_mulai', today())
            ->orderBy('tanggal_mulai')
            ->get();

        $menungguKunci = PeminjamanRuangan::whereIn('status', ['disetujui', 'berjalan'])
            ->whereDate('tanggal_mulai', today())
            ->whereNull('waktu_kunci_diambil')
            ->count();

        return view('pamdal.dashboard', compact(
            'peminjamansHariIni',
            'menungguKunci'
        ));
    }
}