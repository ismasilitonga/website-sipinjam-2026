<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;

class RiwayatBarangPicController extends Controller
{
    public function index(Request $request)
    {
        $riwayat = PeminjamanBarang::with(['user', 'barang'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest('tanggal_pinjam')
            ->paginate(10)
            ->withQueryString();

        return view('shared.riwayat-barang', compact('riwayat'));
    }

    public function detail($id)
    {
        $peminjaman = PeminjamanBarang::with(['user', 'barang'])->findOrFail($id);
        return view('pic.detail-barang', compact('peminjaman'));
    }
}