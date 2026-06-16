<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanBarang;
use App\Models\Insiden;

class DashboardAnggotaController extends Controller
{
   public function index()
{
    $userId = Auth::id();

    $totalPeminjaman = PeminjamanRuangan::where('user_id', $userId)->count();
    $totalInsiden    = Insiden::where('user_id', $userId)->count();
    $disetujui       = PeminjamanRuangan::where('user_id', $userId)->where('status', 'disetujui')->count();
    $menunggu        = PeminjamanRuangan::where('user_id', $userId)->whereIn('status', ['menunggu_ketua', 'menunggu_pic'])->count();

    $ruanganAktifHariIni = PeminjamanRuangan::with(['ruangan', 'user'])
        ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
        ->whereDate('tanggal_mulai', today())
        ->orderBy('tanggal_mulai')
        ->get();

    return view('anggota.dashboard', compact(
        'totalPeminjaman', 'totalInsiden',
        'disetujui', 'menunggu',
        'ruanganAktifHariIni'
    ));
}
}
