<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;

class DashboardKetuaController extends Controller
{
    public function index()
    {
        $ormawa = Auth::user()->organisasi;

        $menungguPersetujuan = PeminjamanRuangan::where('nama_ormawa', $ormawa)
            ->where('status', 'menunggu_ketua')
            ->count();
            
        $diteruskanPic = PeminjamanRuangan::where('nama_ormawa', $ormawa)
            ->where('status', 'menunggu_pic')
            ->count();

        $disetujuiAktif = PeminjamanRuangan::where('nama_ormawa', $ormawa)
            ->where('status', 'disetujui')
            ->count();

        $totalPengajuan = PeminjamanRuangan::where('nama_ormawa', $ormawa)->count();

        $ruanganAktifHariIni = PeminjamanRuangan::with(['ruangan', 'user', 'checkInHariIni'])
            ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->orderBy('tanggal_mulai')
            ->get();

        return view('ketua.dashboard', compact(
            'menungguPersetujuan',
            'diteruskanPic',
            'disetujuiAktif',
            'totalPengajuan',
            'ruanganAktifHariIni'
        ));
    }
}