<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use App\Models\Insiden;
use App\Models\Ruangan;
use App\Models\Barang;

class DashboardPicController extends Controller
{
    public function index()
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $menungguValidasi = PeminjamanRuangan::where('status', 'menunggu_pic')
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->count();

        $insidenAktif = Insiden::whereIn('status', ['dilaporkan', 'ditindaklanjuti'])->count();
        $totalRuangan = Ruangan::count();
        $totalBarang  = Barang::count();
        $ruangans     = Ruangan::all();

        $pengajuanTerbaru = PeminjamanRuangan::with(['user', 'ruangan'])
            ->where('status', 'menunggu_pic')
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->latest()
            ->take(5)
            ->get();

        // FIX: sama seperti Ketua — pakai relasi standar 'checkInHariIni'
        // dan HAPUS blok ->each(...) manual di bawah, karena status
        // sekarang dihitung konsisten lewat accessor $p->status_hari_ini.
        $ruanganAktifHariIni = PeminjamanRuangan::with(['ruangan', 'user', 'checkInHariIni'])
            ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->orderBy('tanggal_mulai')
            ->get();

        return view('pic.dashboard', compact(
            'menungguValidasi', 'insidenAktif',
            'totalRuangan', 'totalBarang', 'pengajuanTerbaru', 'ruangans',
            'ruanganAktifHariIni'
        ));
    }
}