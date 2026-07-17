<?php

namespace App\Http\Controllers\Pamdal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PeminjamanRuangan;
use App\Models\CheckIn;
use Carbon\Carbon;

class KonfirmasiKunciController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = PeminjamanRuangan::with(['user', 'ruangan', 'checkIns'])
            ->whereIn('status', ['disetujui', 'berjalan', 'selesai'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_ormawa', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('ruangan', function ($ruangan) use ($search) {
                            $ruangan->where('nama_ruangan', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            return response()->json(
                $query->get()->map(fn ($p) => $this->transform($p))
            );
        }

        $peminjaman_ruangans = $query->paginate(15)->withQueryString();
        $peminjaman_ruangans->through(fn ($p) => (object) $this->transform($p));

        return view('pamdal.daftar-peminjaman', compact('peminjaman_ruangans', 'search'));
    }

private function transform(PeminjamanRuangan $p): array
{
    $checkinRelevan = $p->checkIns->sortByDesc('id')->first();

    $mulai   = Carbon::parse($p->tanggal_mulai);
    $selesai = Carbon::parse($p->tanggal_selesai);
    $multiHari = !$mulai->isSameDay($selesai);

    return [
        'id' => $p->id,
        'user_nama' => $p->user->nama ?? '-',
        'nama_ormawa' => $p->nama_ormawa,
        'ruangan_nama' => $p->ruangan->nama_ruangan ?? '-',
        'tanggal_mulai' => $mulai->format('d M Y'),
        'tanggal_selesai' => $selesai->format('d M Y'),
        'multi_hari' => $multiHari,
        'jam_mulai' => $mulai->format('H:i'),
        'jam_selesai' => $selesai->format('H:i'),
        'sudah_checkin' => (bool) $checkinRelevan,
        'sudah_checkout' => $checkinRelevan && $checkinRelevan->waktu_checkout ? true : false,
        'waktu_checkin' => $checkinRelevan?->waktu_checkin
            ? Carbon::parse($checkinRelevan->waktu_checkin)->format('H:i')
            : null,
        'waktu_checkout' => $checkinRelevan?->waktu_checkout
            ? Carbon::parse($checkinRelevan->waktu_checkout)->format('H:i')
            : null,
        'foto_ktp_url' => $checkinRelevan && $checkinRelevan->foto_ktp
            ? Storage::url($checkinRelevan->foto_ktp)
            : null,
    ];
}

private function formatDurasi(Carbon $mulai, Carbon $selesai): string
{
    $menitTotal = $mulai->diffInMinutes($selesai);

    $hari  = intdiv($menitTotal, 60 * 24);
    $jam   = intdiv($menitTotal % (60 * 24), 60);
    $menit = $menitTotal % 60;

    $bagian = [];
    if ($hari > 0)  $bagian[] = "{$hari} hari";
    if ($jam > 0)   $bagian[] = "{$jam} jam";
    if ($menit > 0) $bagian[] = "{$menit} menit";

    return implode(' ', $bagian);
}
    public function konfirmasiAmbil($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $sudahCheckinHariIni = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahCheckinHariIni) {
            return back()->with('error', 'Peminjaman ini sudah check-in untuk hari ini.');
        }

        CheckIn::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal'       => today(),
            'foto_ktp'      => null, 
            'waktu_checkin' => now(),
            'status_kunci'  => 'diambil',
        ]);

        $peminjaman->update(['status' => 'berjalan']);

        return back()->with('success', 'Check-in manual berhasil dikonfirmasi oleh Pamdal.');
    }

    public function konfirmasiKembali($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $checkinHariIni = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->first();

        if (!$checkinHariIni) {
            return back()->with('error', 'Belum ada check-in untuk hari ini pada peminjaman ini.');
        }

        if ($checkinHariIni->waktu_checkout) {
            return back()->with('error', 'Peminjaman ini sudah check-out untuk hari ini.');
        }

        $checkinHariIni->update([
            'waktu_checkout' => now(),
            'status_kunci'   => 'dikembalikan',
        ]);

        $hariTerakhir = Carbon::parse($peminjaman->tanggal_selesai)->isSameDay(today());

        $peminjaman->update([
            'status' => $hariTerakhir ? 'selesai' : 'berjalan',
        ]);

        return back()->with('success', 'Check-out manual berhasil dikonfirmasi oleh Pamdal.'
            . ($hariTerakhir ? ' Peminjaman ditandai selesai.' : ' Peminjaman masih berjalan untuk hari berikutnya.'));
    }
}