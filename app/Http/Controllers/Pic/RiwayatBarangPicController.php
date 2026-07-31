<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;

class RiwayatBarangPicController extends Controller
{
    public function index(Request $request)
    {
        $lantai = (string) auth()->user()->lantai_pic;

$riwayat = PeminjamanBarang::with(['user', 'barang'])
    ->whereHas('barang', function ($q) use ($lantai) {
        $q->whereHas('ruangan', fn($qr) => $qr->where('lantai', $lantai))
          ->orWhereNull('ruangan_id');
    })
    ->where('status', '!=', 'selesai')
    ->when($request->status, fn($q, $s) => $q->where('status', $s))
    ->latest('tanggal_pinjam')
    ->paginate(10)
    ->withQueryString();

        return view('shared.riwayat-barang', compact('riwayat'));
    }

    public function detail($id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $peminjaman = PeminjamanBarang::with(['user', 'barang'])
            ->whereHas('barang', function ($q) use ($lantai) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('lantai', $lantai))
                  ->orWhereNull('ruangan_id');
            })
            ->findOrFail($id);

        return view('pic.detail-barang', compact('peminjaman'));
    }
}