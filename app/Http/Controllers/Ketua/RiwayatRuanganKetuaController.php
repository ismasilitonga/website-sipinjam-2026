<?php

namespace App\Http\Controllers\Ketua;

use App\Models\Ruangan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;

class RiwayatRuanganKetuaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $filters = [
            ''               => 'Semua',
            'menunggu_ketua' => 'Menunggu Ketua',
            'menunggu_pic'   => 'Menunggu PIC',
            'disetujui'      => 'Disetujui',
            'ditolak'        => 'Ditolak',
            'berjalan'       => 'Berjalan',
            'selesai'        => 'Selesai',
        ];

        $query = PeminjamanRuangan::with('ruangan')
            ->where('nama_ormawa', $user->organisasi)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('tanggal'), fn($q) => $q->whereDate('tanggal_mulai', $request->tanggal))
            ->when($request->filled('minggu'), function ($q) use ($request) {
                [$year, $week] = explode('-W', $request->minggu);
                $q->whereYear('tanggal_mulai', $year)
                  ->whereRaw('WEEK(tanggal_mulai, 1) = ?', [(int)$week]);
            })
            ->when($request->filled('bulan'), function ($q) use ($request) {
                [$year, $month] = explode('-', $request->bulan);
                $q->whereYear('tanggal_mulai', $year)
                  ->whereMonth('tanggal_mulai', $month);
            })
            ->when($request->filled('ruangan_id'), fn($q) => $q->where('ruangan_id', $request->ruangan_id));

        $riwayat        = (clone $query)->latest()->paginate(10)->withQueryString();
        $totalSelesai   = (clone $query)->where('status', 'selesai')->count();
        $totalDisetujui = (clone $query)->where('status', 'disetujui')->count();
        $ruangans       = Ruangan::orderBy('nama_ruangan')->get();

        return view('shared.riwayat-ruangan', compact(
            'riwayat', 'filters', 'totalSelesai', 'totalDisetujui', 'ruangans'
        ));
    }

    public function show($id)
    {
        $peminjaman = PeminjamanRuangan::with(['ruangan', 'user'])
            ->where('nama_ormawa', Auth::user()->organisasi)
            ->findOrFail($id);

        return view('ketua.detail-peminjaman', compact('peminjaman'));
    }
}