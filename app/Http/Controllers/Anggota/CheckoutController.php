<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\CheckIn;
use Carbon\Carbon;

class CheckoutController extends Controller
{
   
    private function scopeBukanDitolak($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('status_verifikasi')
              ->orWhere('status_verifikasi', '!=', 'ditolak');
        });
    }

    public function index()
    {
        $peminjaman_list = PeminjamanRuangan::with(['ruangan', 'checkIns' => function ($q) {
                $q->whereNull('waktu_checkout');
                $this->scopeBukanDitolak($q);
                $q->oldest('tanggal'); 
            }])
            ->where('user_id', Auth::id())
            ->where('status', 'berjalan')
            ->whereHas('checkIns', function ($q) {
                $q->whereNull('waktu_checkout');
                $this->scopeBukanDitolak($q);
            })
            ->get();

        $sesi_list = collect();

        foreach ($peminjaman_list as $p) {
            $jamSelesaiWaktu = Carbon::parse($p->tanggal_selesai)->format('H:i:s');

            foreach ($p->checkIns as $index => $checkin) {
                $tanggalAcuan = Carbon::parse($checkin->tanggal)->toDateString();

                $checkin->peminjamanRef  = $p;
                $checkin->batas_checkout = Carbon::parse($tanggalAcuan . ' ' . $jamSelesaiWaktu);
                $checkin->boleh_checkout = now()->gte($checkin->batas_checkout);
                $checkin->checkout_telat = !Carbon::parse($checkin->tanggal)->isSameDay(today());

                $checkin->urutan_ke = $index; 
                $checkin->harus_checkout_dulu = $index > 0;

                $sesi_list->push($checkin);
            }
        }

        return view('anggota.checkout', ['sesi_list' => $sesi_list]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'checkin_id' => 'required|exists:check_in,id',
        ]);

        $checkinQuery = CheckIn::whereKey($request->checkin_id)
            ->whereNull('waktu_checkout');
        $checkinQuery = $this->scopeBukanDitolak($checkinQuery);
        $checkin = $checkinQuery->firstOrFail();

        $peminjaman = PeminjamanRuangan::where('id', $checkin->peminjaman_id)
            ->where('user_id', Auth::id())
            ->where('status', 'berjalan')
            ->firstOrFail();

        $adaSesiLebihLamaQuery = $peminjaman->checkIns()
            ->whereNull('waktu_checkout')
            ->whereDate('tanggal', '<', $checkin->tanggal);
        $adaSesiLebihLamaQuery = $this->scopeBukanDitolak($adaSesiLebihLamaQuery);
        $adaSesiLebihLamaMenunggak = $adaSesiLebihLamaQuery->exists();

        if ($adaSesiLebihLamaMenunggak) {
            return redirect()->back()->with('error',
                'Masih ada sesi hari sebelumnya yang belum di-checkout untuk peminjaman ini. ' .
                'Selesaikan checkout hari yang lebih lama terlebih dahulu.');
        }

        $jamSelesaiWaktu = Carbon::parse($peminjaman->tanggal_selesai)->format('H:i:s');
        $tanggalAcuan    = Carbon::parse($checkin->tanggal)->toDateString();
        $batasCheckout   = Carbon::parse($tanggalAcuan . ' ' . $jamSelesaiWaktu);

        if (now()->lt($batasCheckout)) {
            return redirect()->back()->with('error',
                'Belum waktunya check-out. Check-out baru bisa dilakukan tepat pada jam ' .
                $batasCheckout->format('H:i') . ' sesuai jadwal peminjaman.');
        }

        $checkin->update([
            'waktu_checkout'          => now(),
            'status_checkout'         => 'menunggu',
            'alasan_checkout_ditolak' => null,
        ]);

        $tanggalSelesaiBooking = Carbon::parse($peminjaman->tanggal_selesai)->toDateString();

        if ($tanggalAcuan === $tanggalSelesaiBooking) {
            $peminjaman->update(['status' => 'selesai']);
        }

        return redirect()
            ->route('anggota.riwayat-ruangan')
            ->with('success', 'Check-out berhasil dicatat! Segera kembalikan kunci ke petugas Pamdal untuk menyelesaikan proses peminjaman hari ini.');
    }
}