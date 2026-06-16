<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request; 

class RiwayatRuanganAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            ''                 => 'Semua',
            'menunggu_ketua'   => 'Menunggu Ketua',
            'menunggu_pic'     => 'Menunggu PIC',
            'disetujui'        => 'Disetujui',
            'ditolak'          => 'Ditolak',
            'berjalan'         => 'Berjalan',
            'selesai'          => 'Selesai',
        ];

        $riwayat = PeminjamanRuangan::with('ruangan')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('anggota.riwayat-ruangan', compact('riwayat', 'filters'));
    }
}