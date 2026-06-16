<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request; 

class RiwayatRuanganPicController extends Controller
{
    public function index(Request $request)
    {
         $lantai = (string) auth()->user()->lantai_pic;
        $filters = [
            ''                 => 'Semua',
            'menunggu_ketua'   => 'Menunggu Ketua',
            'menunggu_pic'     => 'Menunggu PIC',
            'disetujui'        => 'Disetujui',
            'ditolak'          => 'Ditolak',
            'berjalan'         => 'Berjalan',
            'selesai'          => 'Selesai',
        ];
        $riwayat = PeminjamanRuangan::with(['user', 'ruangan'])
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->whereHas('ruangan', function($q) use ($lantai) {
                $q->where('lantai', $lantai);
            })
            ->latest('tanggal_mulai')
            ->paginate(10)
            ->withQueryString();

        return view('shared.riwayat-ruangan', compact('riwayat', 'filters'));
    }
}