<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandoverPicController extends Controller
{
public function index()
{
    $menungguSerah = PeminjamanBarang::with(['user', 'barang'])
        ->where('status', 'disetujui')
        ->whereNull('waktu_diserahkan')
        ->latest()->get();

    $menungguKembali = PeminjamanBarang::with(['user', 'barang'])
        ->where('status', 'disetujui')
        ->whereNotNull('waktu_diserahkan')
        ->whereNull('waktu_diterima_kembali')
        ->latest()->get();

    $riwayat = PeminjamanBarang::with(['user', 'barang'])  
        ->where('status', 'disetujui')
        ->whereNotNull('waktu_diserahkan')
        ->whereNotNull('waktu_diterima_kembali')
        ->latest()->get();

    return view('pic.serah-terima', compact('menungguSerah', 'menungguKembali', 'riwayat'));
}

    public function konfirmasi(Request $request, $id)
{
    $request->validate([
        'foto_serah' => 'nullable|image|max:2048',
    ]);

    $peminjaman = PeminjamanBarang::findOrFail($id);

    $data = [
        'waktu_diserahkan' => now(),
        'diserahkan_oleh'  => Auth::id(),
    ];
    if ($request->hasFile('foto_serah')) {
        $data['foto_serah'] = $request->file('foto_serah')->store('foto-serah', 'public');
    }
    $peminjaman->update($data);

    return redirect()->route('pic.serah-terima')->with('success', 'Penyerahan barang berhasil dikonfirmasi.');
    }

    public function terimaKembali(Request $request, $id)
    {
    $request->validate([
        'foto_kembali' => 'nullable|image|max:2048',
    ]);

    $peminjaman = PeminjamanBarang::findOrFail($id);

    $data = [
        'waktu_diterima_kembali' => now(),
        'diterima_oleh'          => Auth::id(),
        'kondisi_barang'         => $request->kondisi_barang,
        'catatan_kondisi'        => $request->catatan_kondisi,
    ];

    if ($request->hasFile('foto_kembali')) {
        $data['foto_kembali'] = $request->file('foto_kembali')->store('foto-kembali', 'public');
    }

    $peminjaman->update($data);

    return redirect()->route('pic.serah-terima')->with('success', 'Penerimaan barang kembali berhasil dikonfirmasi.');
}
}