<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index()
    {
        $peminjaman_ruangan = PeminjamanRuangan::with(['ruangan', 'checkInHariIni'])
            ->where('user_id', Auth::id())
            ->where('status', 'berjalan')
            ->whereHas('checkIns', function ($q) {
                $q->whereDate('tanggal', today())->whereNull('waktu_checkout');
            })
            ->get();

        return view('anggota.checkout', compact('peminjaman_ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_ruangan,id',
        ]);

        $peminjaman = PeminjamanRuangan::where('id', $request->peminjaman_id)
            ->where('user_id', Auth::id())
            ->where('status', 'berjalan')
            ->firstOrFail();

        $checkInHariIni = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->whereNull('waktu_checkout')
            ->firstOrFail(); 
        $checkInHariIni->update([
            'waktu_checkout' => now(),
            'status_kunci'   => 'dikembalikan',
        ]);

        $hariTerakhir = Carbon::parse($peminjaman->tanggal_selesai)->isSameDay(today());

        $peminjaman->update([
            'status' => $hariTerakhir ? 'selesai' : 'berjalan',
        ]);

        return redirect()
            ->route('anggota.riwayat-ruangan', ['status' => $hariTerakhir ? 'selesai' : 'berjalan'])
            ->with('success', 'Check-out berhasil!');
    }
}