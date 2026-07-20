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
                $q->whereDate('tanggal', today())
                  ->whereNull('waktu_checkout')
                  ->where('status_verifikasi', '!=', 'ditolak');
            })
            ->get();

        foreach ($peminjaman_ruangan as $p) {
            $jamSelesaiWaktu = Carbon::parse($p->tanggal_selesai)->format('H:i:s');
            $p->batas_checkout = Carbon::parse(today()->toDateString() . ' ' . $jamSelesaiWaktu);
            $p->boleh_checkout = now()->gte($p->batas_checkout);
        }

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
            ->where('status_verifikasi', '!=', 'ditolak')
            ->firstOrFail();

        $jamSelesaiWaktu = Carbon::parse($peminjaman->tanggal_selesai)->format('H:i:s');
        $batasCheckout   = Carbon::parse(today()->toDateString() . ' ' . $jamSelesaiWaktu);

        if (now()->lt($batasCheckout)) {
            return redirect()->back()->with('error',
                'Belum waktunya check-out. Check-out baru bisa dilakukan tepat pada jam ' .
                $batasCheckout->format('H:i') . ' sesuai jadwal peminjaman.');
        }

        $checkInHariIni->update([
            'waktu_checkout'          => now(),
            'status_checkout'         => 'menunggu',
            'alasan_checkout_ditolak' => null,
        ]);

        $tanggalSelesaiBooking = Carbon::parse($peminjaman->tanggal_selesai)->toDateString();

        if (today()->toDateString() === $tanggalSelesaiBooking) {
            $peminjaman->update(['status' => 'selesai']);
        }

        return redirect()
            ->route('anggota.riwayat-ruangan')
            ->with('success', 'Check-out berhasil dicatat! Segera kembalikan kunci ke petugas Pamdal untuk menyelesaikan proses peminjaman hari ini.');
    }
}