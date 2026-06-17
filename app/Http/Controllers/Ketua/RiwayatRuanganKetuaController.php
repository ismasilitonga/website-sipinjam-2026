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
        ''                 => 'Semua',
        'menunggu_ketua'   => 'Menunggu Ketua',
        'menunggu_pic'     => 'Menunggu PIC',
        'disetujui'        => 'Disetujui',
        'ditolak'          => 'Ditolak',
        'berjalan'         => 'Berjalan',
        'selesai'          => 'Selesai',
    ];

    $query = PeminjamanRuangan::with('ruangan')
        ->where('nama_ormawa', $user->organisasi)
        ->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

    $riwayat = $query->latest()
        ->paginate(10)
        ->withQueryString();

    $totalSelesai = (clone $query)
        ->where('status', 'selesai')
        ->count();

    $totalDisetujui = (clone $query)
        ->where('status', 'disetujui')
        ->count();
    $ruangans = Ruangan::orderBy('nama_ruangan')->get();
    return view('shared.riwayat-ruangan', compact(
        'riwayat',
        'filters',
        'totalSelesai',
        'totalDisetujui',
        'ruangans'
    ));
}
}