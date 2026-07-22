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
        $mulai   = Carbon::parse($p->tanggal_mulai);
        $selesai = Carbon::parse($p->tanggal_selesai);
        $multiHari = !$mulai->isSameDay($selesai);

        $todayInRange = today()->betweenIncluded($mulai->copy()->startOfDay(), $selesai->copy()->endOfDay());

        if ($todayInRange) {
            $checkinRelevan = $p->checkIns->first(fn ($c) => Carbon::parse($c->tanggal)->isSameDay(today()));
        } else {
            $checkinRelevan = $p->checkIns->sortByDesc('id')->first();
        }

        $kunciLamaBelumKembali = $p->checkIns
            ->filter(fn ($c) => !Carbon::parse($c->tanggal)->isSameDay(today())
                && $c->kunci_diambil_pamdal_at
                && !$c->kunci_dikembalikan_pamdal_at)
            ->sortByDesc('tanggal')
            ->first();

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

            'sudah_ambil_kunci' => (bool) $checkinRelevan?->kunci_diambil_pamdal_at,
            'sudah_kembali_kunci' => (bool) $checkinRelevan?->kunci_dikembalikan_pamdal_at,
            'waktu_ambil_kunci' => $checkinRelevan?->kunci_diambil_pamdal_at
                ? Carbon::parse($checkinRelevan->kunci_diambil_pamdal_at)->format('H:i')
                : null,
            'waktu_kembali_kunci' => $checkinRelevan?->kunci_dikembalikan_pamdal_at
                ? Carbon::parse($checkinRelevan->kunci_dikembalikan_pamdal_at)->format('H:i')
                : null,
            'foto_ktp_url' => $checkinRelevan && $checkinRelevan->foto_ktp
                ? route('pamdal.foto-identitas.show', $checkinRelevan->id)
                : null,

            'status_verifikasi' => $checkinRelevan?->status_verifikasi,
            'alasan_verifikasi_ditolak' => $checkinRelevan?->alasan_verifikasi_ditolak,

            'status_checkout' => $checkinRelevan?->status_checkout,
            'alasan_checkout_ditolak' => $checkinRelevan?->alasan_checkout_ditolak,

            'kunci_lama_belum_kembali' => (bool) $kunciLamaBelumKembali,
            'tanggal_kunci_lama' => $kunciLamaBelumKembali
                ? Carbon::parse($kunciLamaBelumKembali->tanggal)->format('d M Y')
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

        $kunciLamaBelumKembali = $peminjaman->checkIns()
            ->whereDate('tanggal', '<', today())
            ->whereNotNull('kunci_diambil_pamdal_at')
            ->whereNull('kunci_dikembalikan_pamdal_at')
            ->latest('tanggal')
            ->first();

        if ($kunciLamaBelumKembali) {
            $tgl = Carbon::parse($kunciLamaBelumKembali->tanggal)->format('d M Y');
            return back()->with('error',
                "Kunci dari tanggal {$tgl} untuk peminjaman ini belum dikonfirmasi kembali. " .
                "Konfirmasikan dulu pengembalian kunci sebelumnya sebelum memberikan kunci baru.");
        }

        $checkin = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->whereNull('kunci_diambil_pamdal_at')
            ->latest('id')
            ->first();

        if (!$checkin) {
            return back()->with('error',
                'Anggota belum check-in untuk hari ini. Kunci tidak dapat diberikan sebelum ' .
                'anggota check-in & upload foto KTP/KTM melalui aplikasi.');
        }

        if ($checkin->status_verifikasi === 'ditolak') {
            return back()->with('error', 'Verifikasi data diri peminjam ini masih ditolak. Minta peminjam upload ulang foto KTP terlebih dahulu.');
        }

        $checkin->update([
            'kunci_diambil_pamdal_at' => now(),
            'status_kunci' => 'diambil',
        ]);

        $peminjaman->update([
            'status'              => 'berjalan',
            'waktu_kunci_diambil' => $peminjaman->waktu_kunci_diambil ?? now(),
        ]);

        return back()->with('success', 'Pengambilan kunci berhasil dikonfirmasi oleh Pamdal.');
    }

    public function konfirmasiKembali($id)
{
    $peminjaman = PeminjamanRuangan::findOrFail($id);

    $checkinBelumKembaliKunci = $peminjaman->checkIns()
        ->whereNotNull('kunci_diambil_pamdal_at')
        ->whereNull('kunci_dikembalikan_pamdal_at')
        ->latest('tanggal')
        ->first();

    if (!$checkinBelumKembaliKunci) {
        return back()->with('error', 'Tidak ada kunci yang menunggu pengembalian pada peminjaman ini. Pastikan kunci sudah dikonfirmasi diambil terlebih dahulu.');
    }

    if (is_null($checkinBelumKembaliKunci->waktu_checkout)) {
        return back()->with('error',
            'Anggota belum melakukan check-out melalui aplikasi. Minta anggota check-out terlebih dahulu ' .
            'sebelum kunci dikonfirmasi kembali, supaya data tercatat konsisten.');
    }

    $checkinBelumKembaliKunci->update([
        'kunci_dikembalikan_pamdal_at' => now(),
        'status_kunci'   => 'dikembalikan',
        'status_checkout' => $checkinBelumKembaliKunci->waktu_checkout ? 'diterima' : $checkinBelumKembaliKunci->status_checkout,
    ]);

    $tanggalCheckinIni     = Carbon::parse($checkinBelumKembaliKunci->tanggal)->toDateString();
    $tanggalSelesaiBooking = Carbon::parse($peminjaman->tanggal_selesai)->toDateString();
    $iniHariTerakhir       = $tanggalCheckinIni === $tanggalSelesaiBooking;

    $peminjaman->update([
        'status'                    => $iniHariTerakhir ? 'selesai' : 'berjalan',
        'waktu_kunci_dikembalikan'  => now(),
    ]);

    return back()->with('success', 'Pengembalian kunci berhasil dikonfirmasi.'
        . ($iniHariTerakhir
            ? ' Peminjaman ditandai selesai.'
            : ' Peminjaman masih berjalan untuk hari berikutnya.'));
}

    public function tolakVerifikasi(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $checkin = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->whereNotNull('foto_ktp')
            ->latest('id')
            ->first();

        if (!$checkin) {
            return back()->with('error', 'Tidak ada foto KTP hari ini yang bisa ditolak untuk peminjaman ini.');
        }

        $checkin->update([
            'status_verifikasi'         => 'ditolak',
            'alasan_verifikasi_ditolak' => $request->alasan,
        ]);

        return back()->with('success', 'Verifikasi data diri ditolak. Peminjam akan diminta upload ulang foto KTP.');
    }

    public function tolakCheckout(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $checkin = $peminjaman->checkIns()
            ->whereDate('tanggal', today())
            ->whereNotNull('waktu_checkout')
            ->whereNull('kunci_dikembalikan_pamdal_at')
            ->latest('id')
            ->first();

        if (!$checkin) {
            return back()->with('error', 'Tidak ada klaim checkout hari ini yang bisa ditolak untuk peminjaman ini.');
        }

        $checkin->update([
            'waktu_checkout'          => null,
            'status_checkout'         => 'ditolak',
            'alasan_checkout_ditolak' => $request->alasan,
        ]);

        return back()->with('success', 'Klaim checkout ditolak. Peminjam akan diminta checkout ulang setelah benar-benar mengembalikan kunci.');
    }

    public function showKtp($filename)
    {
        $path = storage_path('app/public/ktp/' . $filename);
        abort_unless(file_exists($path), 404);
        return response()->file($path);
    }
}