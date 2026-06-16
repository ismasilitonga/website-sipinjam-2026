<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanBarang;

class HandoverPicController extends Controller
{
    public function index()
    {
        $menungguSerah = PeminjamanBarang::with(['user', 'barang'])
            ->where('status', 'disetujui')
            ->whereNull('waktu_diserahkan')
            ->latest()
            ->get();

        $menungguKembali = PeminjamanBarang::with(['user', 'barang'])
            ->where('status', 'disetujui')
            ->whereNotNull('waktu_diserahkan')
            ->whereNull('waktu_diterima_kembali')
            ->latest()
            ->get();

        return view('pic.serah-terima', compact('menungguSerah', 'menungguKembali'));
    }

    public function konfirmasi(Request $request, $id)
    {
        PeminjamanBarang::findOrFail($id)->update([
            'waktu_diserahkan'   => now(),
            'diserahkan_oleh' => Auth::id(),
        ]);

        return back()->with('success', 'Penyerahan barang berhasil dikonfirmasi.');
    }

    public function terimaKembali(Request $request, $id)
    {
        PeminjamanBarang::findOrFail($id)->update([
            'waktu_diterima_kembali' => now(),
            'diterima_oleh'       => Auth::id(),
        ]);

        return back()->with('success', 'Penerimaan barang kembali berhasil dikonfirmasi.');
    }
}
