<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;

class CheckoutController extends Controller
{
    public function index()
{
    $peminjaman_ruangan = PeminjamanRuangan::with(['ruangan', 'checkIn'])
        ->where('user_id', Auth::id())
        ->where('status', 'berjalan')
        ->get();

    return view('anggota.checkout', compact('peminjaman_ruangan'));
}

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_ruangan,id', 
        ]);

        $peminjaman = PeminjamanRuangan::with('checkIn')
            ->where('id', $request->peminjaman_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $checkIn = $peminjaman->checkIn ?? $peminjaman->checkIn()->create([
            'status_kunci' => 'belum_diambil',
        ]);

        $checkIn->update([
            'waktu_checkout' => now(),
            'status_kunci'   => 'dikembalikan',
        ]);

        $peminjaman->update(['status' => 'selesai']);

        return redirect()
            ->route('anggota.riwayat-ruangan', ['status' => 'selesai'])
            ->with('success', 'Check-out berhasil!');
    }
}