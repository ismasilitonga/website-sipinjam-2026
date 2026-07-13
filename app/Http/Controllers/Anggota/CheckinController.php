<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\CheckIn;
use Carbon\Carbon;

class CheckinController extends Controller
{

    const TOLERANSI_MENIT_SEBELUM = 15;

    public function index()
    {
        $peminjaman_ruangan = PeminjamanRuangan::with('ruangan')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->whereDoesntHave('checkIn')
            ->whereDate('tanggal_mulai', today())
            ->get();

        return view('anggota.checkin', compact('peminjaman_ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_ruangan,id',
            'foto_ktp'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $peminjaman = PeminjamanRuangan::where('id', $request->peminjaman_id)
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->whereDoesntHave('checkIn')
            ->whereDate('tanggal_mulai', today())
            ->first();

        if (!$peminjaman) {
            return redirect()->back()->with('error',
                'Check-in tidak dapat dilakukan. Pastikan pengajuan sudah disetujui, jadwalnya hari ini, dan belum pernah check-in sebelumnya.');
        }

        $sekarang       = now();
        $batasMulai     = Carbon::parse($peminjaman->tanggal_mulai)->subMinutes(self::TOLERANSI_MENIT_SEBELUM);
        $batasSelesai   = Carbon::parse($peminjaman->tanggal_selesai);

        if ($sekarang->lt($batasMulai)) {
            return redirect()->back()->with('error',
                'Belum waktunya check-in. Check-in bisa dilakukan mulai ' .
                $batasMulai->format('H:i') . ' (' . self::TOLERANSI_MENIT_SEBELUM . ' menit sebelum jadwal).');
        }

        if ($sekarang->gt($batasSelesai)) {
            return redirect()->back()->with('error',
                'Jadwal peminjaman sudah lewat. Check-in tidak dapat dilakukan lagi untuk pengajuan ini.');
        }

        $path = $request->file('foto_ktp')->store('ktp', 'public');

        CheckIn::create([
            'peminjaman_id' => $peminjaman->id,
            'foto_ktp'      => $path,
            'waktu_checkin' => now(),
            'status_kunci'  => 'diambil',
        ]);

        $peminjaman->update(['status' => 'berjalan']);

        return redirect()->back()->with('success', 'Check-in berhasil!');
    }
}