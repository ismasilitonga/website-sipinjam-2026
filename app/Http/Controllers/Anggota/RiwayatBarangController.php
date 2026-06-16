<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request; 

class RiwayatBarangController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            ''                 => 'Semua',
            'menunggu_pic'     => 'Menunggu PIC',
            'disetujui'        => 'Disetujui',
            'ditolak'          => 'Ditolak',
        ];

        $riwayat = PeminjamanBarang::with('barang')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest('tanggal_pinjam') 
            ->paginate(10)
            ->withQueryString();

        return view('anggota.riwayat-barang', compact('riwayat', 'filters'));
    }
}