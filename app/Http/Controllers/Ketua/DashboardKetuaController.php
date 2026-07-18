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

        // FIX: sebelumnya dihitung dari collection $peminjamanAktif yang
        // dibatasi take(5), jadi angkanya bisa salah kalau data lebih
        // dari 5 (dan "Total Pengajuan" cuma nampilin 5 terakhir, bukan
        // keseluruhan). Sekarang dihitung langsung dari DB.
        $diteruskanPic = PeminjamanRuangan::where('nama_ormawa', $ormawa)
            ->where('status', 'menunggu_pic')
            ->count();

        $disetujuiAktif = PeminjamanRuangan::where('nama_ormawa', $ormawa)
            ->where('status', 'disetujui')
            ->count();

        $totalPengajuan = PeminjamanRuangan::where('nama_ormawa', $ormawa)->count();

        // FIX: ganti eager-load closure manual 'checkIns' => whereDate(...)
        // menjadi relasi standar 'checkInHariIni' (sudah ada di model),
        // dan HAPUS blok ->each(...) di bawah karena logic status
        // sekarang terpusat di accessor $p->status_hari_ini pada model.
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