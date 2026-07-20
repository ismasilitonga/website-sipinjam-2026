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
    const TOLERANSI_MENIT_SEBELUM = 0;

    public function index()
    {

        $peminjaman_ruangan = PeminjamanRuangan::with(['ruangan', 'checkIns' => function ($q) {
                $q->whereDate('tanggal', today());
            }])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['disetujui', 'berjalan'])
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->whereDoesntHave('checkIns', function ($q) {
                $q->whereDate('tanggal', today())
                  ->where('status_verifikasi', '!=', 'ditolak');
            })
            ->get();

        foreach ($peminjaman_ruangan as $p) {
            $jamMulai   = Carbon::parse($p->tanggal_mulai)->format('H:i:s');
            $jamSelesai = Carbon::parse($p->tanggal_selesai)->format('H:i:s');

            $batasMulai   = Carbon::parse(today()->toDateString() . ' ' . $jamMulai)
                                ->subMinutes(self::TOLERANSI_MENIT_SEBELUM);
            $batasSelesai = Carbon::parse(today()->toDateString() . ' ' . $jamSelesai);

            $p->batas_mulai_checkin = $batasMulai;
            $p->batas_selesai_checkin = $batasSelesai;

            $p->ruangan_sedang_dipakai = $this->ruanganMasihDipakai($p);

            $p->boleh_checkin = now()->gte($batasMulai)
                && now()->lte($batasSelesai)
                && !$p->ruangan_sedang_dipakai;

            $checkinDitolakHariIni = $p->checkIns->firstWhere('status_verifikasi', 'ditolak');
            $p->alasan_ditolak = $checkinDitolakHariIni?->alasan_verifikasi_ditolak;
        }

        return view('anggota.checkin', compact('peminjaman_ruangan'));
    }

    private function ruanganMasihDipakai(PeminjamanRuangan $peminjaman): bool
    {
        return CheckIn::whereDate('tanggal', today())
            ->whereNull('waktu_checkout')
            ->whereHas('peminjaman', function ($q) use ($peminjaman) {
                $q->where('ruangan_id', $peminjaman->ruangan_id)
                  ->where('id', '!=', $peminjaman->id);
            })
            ->exists();
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_ruangan,id',
            'foto_ktp'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $peminjaman = PeminjamanRuangan::where('id', $request->peminjaman_id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['disetujui', 'berjalan'])
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>=', today())
            ->whereDoesntHave('checkIns', function ($q) {
                $q->whereDate('tanggal', today())
                  ->where('status_verifikasi', '!=', 'ditolak');
            })
            ->first();

        if (!$peminjaman) {
            return redirect()->back()->with('error',
                'Check-in tidak dapat dilakukan. Pastikan pengajuan sudah disetujui, jadwal hari ini termasuk dalam periode peminjaman, dan belum check-in untuk hari ini.');
        }

        $sekarang = now();
        $jamMulai   = Carbon::parse($peminjaman->tanggal_mulai)->format('H:i:s');
        $jamSelesai = Carbon::parse($peminjaman->tanggal_selesai)->format('H:i:s');

        $batasMulai   = Carbon::parse(today()->toDateString() . ' ' . $jamMulai)
                            ->subMinutes(self::TOLERANSI_MENIT_SEBELUM);
        $batasSelesai = Carbon::parse(today()->toDateString() . ' ' . $jamSelesai);

        if ($sekarang->lt($batasMulai)) {
            return redirect()->back()->with('error',
                'Check-in baru bisa dilakukan tepat pada jam ' .
                $batasMulai->format('H:i') . ' sesuai jadwal peminjaman.');
        }

        if ($sekarang->gt($batasSelesai)) {
            return redirect()->back()->with('error',
                'Jadwal hari ini sudah lewat. Check-in tidak dapat dilakukan lagi.');
        }

        if ($this->ruanganMasihDipakai($peminjaman)) {
            return redirect()->back()->with('error',
                'Ruangan masih digunakan oleh peminjam sebelumnya. Silakan tunggu hingga mereka check-out sebelum check-in.');
        }

        $path = $request->file('foto_ktp')->store('ktp', 'local');

        $checkinDitolakHariIni = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->where('status_verifikasi', 'ditolak')
            ->latest('id')
            ->first();

        if ($checkinDitolakHariIni) {
            $checkinDitolakHariIni->update([
                'foto_ktp'                  => $path,
                'waktu_checkin'             => now(),
                'status_kunci'              => 'belum_diambil',
                'status_verifikasi'         => 'menunggu',
                'alasan_verifikasi_ditolak' => null,
            ]);
        } else {
            CheckIn::create([
                'peminjaman_id' => $peminjaman->id,
                'tanggal'       => today(),
                'foto_ktp'      => $path,
                'waktu_checkin' => now(),
                'status_kunci'  => 'belum_diambil',
                'status_verifikasi' => 'menunggu',
            ]);
        }

        $peminjaman->update(['status' => 'berjalan']);

        return redirect()->back()->with('success', 'Check-in berhasil! Silakan temui petugas Pamdal untuk verifikasi & pengambilan kunci ruangan.');
    }
}