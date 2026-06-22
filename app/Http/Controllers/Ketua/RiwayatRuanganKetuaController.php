<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;

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

        $riwayat = PeminjamanRuangan::with(['ruangan', 'user'])
            ->where('nama_ormawa', $user->organisasi)
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where('status', $request->status)
                )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('ketua.riwayat-peminjaman', compact(
            'riwayat',
            'filters'
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