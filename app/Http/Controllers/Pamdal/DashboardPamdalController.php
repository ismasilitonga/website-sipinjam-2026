<?php

namespace App\Http\Controllers\Pamdal;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;

class DashboardPamdalController extends Controller
{
    public function index()
    {
        $peminjamansHariIni = PeminjamanRuangan::with(['user', 'ruangan', 'checkIns' => function ($q) {
                $q->whereDate('tanggal', today());
            }])
            ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->orderBy('tanggal_mulai')
            ->get();

        $peminjamansHariIni->each(function ($p) {
            $checkinHariIni = $p->checkIns->first(); 
            $p->sudah_ambil_hari_ini   = !is_null($checkinHariIni);
            $p->sudah_kembali_hari_ini = $checkinHariIni && !is_null($checkinHariIni->waktu_checkout);
            $p->waktu_ambil_hari_ini   = $checkinHariIni?->waktu_checkin;
            $p->waktu_kembali_hari_ini = $checkinHariIni?->waktu_checkout;
        });

        $menungguKunci = $peminjamansHariIni->filter(fn ($p) => !$p->sudah_ambil_hari_ini)->count();

        $totalSudahDiambil      = PeminjamanRuangan::whereNotNull('waktu_kunci_diambil')->count();
        $totalSudahDikembalikan = PeminjamanRuangan::whereNotNull('waktu_kunci_dikembalikan')->count();

        return view('pamdal.dashboard', compact(
            'peminjamansHariIni',
            'menungguKunci',
            'totalSudahDiambil',
            'totalSudahDikembalikan'
        ));
    }
}