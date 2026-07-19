<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\Barang;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalUser       = User::where('status', 'aktif')->count();
        $pendaftarBaru   = User::where('status', 'pending')->count();
        $totalPeminjaman = PeminjamanRuangan::count();
        $totalRuangan    = Ruangan::count();

        $ruanganAktifHariIni = PeminjamanRuangan::with(['ruangan', 'user', 'checkInHariIni'])
            ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->orderBy('tanggal_mulai')
            ->get();

        return view('admin.dashboard', compact(
            'totalUser',
            'pendaftarBaru',
            'totalPeminjaman',
            'totalRuangan',
            'ruanganAktifHariIni'
        ));
    }

public function statusPeminjaman()
{
    $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])
        ->whereIn('status', ['menunggu_pic', 'menunggu_ketua', 'ditolak'])
        ->latest()
        ->paginate(10);

    return view('admin.status-peminjaman', compact('peminjaman_ruangans'));
}

    public function detailPeminjaman($id)
    {
        $peminjaman = PeminjamanRuangan::with(['user', 'ruangan'])->findOrFail($id);
        return view('admin.status-peminjaman-detail', compact('peminjaman'));
    }
}