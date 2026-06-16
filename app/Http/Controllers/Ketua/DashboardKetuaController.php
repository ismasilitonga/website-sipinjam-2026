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

    $peminjamanAktif = PeminjamanRuangan::with(['user', 'ruangan'])
        ->where('nama_ormawa', $ormawa)
        ->whereIn('status', ['menunggu_ketua', 'menunggu_pic', 'disetujui'])
        ->latest()
        ->take(5)
        ->get();

    $ruanganAktifHariIni = PeminjamanRuangan::with(['ruangan', 'user'])
        ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
        ->whereDate('tanggal_mulai', today())
        ->orderBy('tanggal_mulai')
        ->get();

    return view('ketua.dashboard', compact(
        'menungguPersetujuan', 'peminjamanAktif', 'ruanganAktifHariIni'
    ));
}
}









