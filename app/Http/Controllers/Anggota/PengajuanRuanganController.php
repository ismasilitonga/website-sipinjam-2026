<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PengajuanRuanganController extends Controller
{
    const JEDA_MENIT = 30;

    const MIN_DURASI_MENIT = 60;

    const STATUS_MENGUNCI = ['disetujui', 'berjalan'];

    public function index()
    {
        $ruangans = Ruangan::where('status', 'tersedia')->get();
        return view('anggota.pengajuan-ruangan', compact('ruangans'));
    }

    private function cariBentrok(int $ruanganId, Carbon $mulai, Carbon $selesai, ?int $kecualikanId = null): ?PeminjamanRuangan
    {
        $tglMulaiBaru   = $mulai->copy()->toDateString();
        $tglSelesaiBaru = $selesai->copy()->toDateString();

        $kandidat = PeminjamanRuangan::where('ruangan_id', $ruanganId)
            ->whereIn('status', self::STATUS_MENGUNCI)
            ->when($kecualikanId, fn ($q) => $q->where('id', '!=', $kecualikanId))
            ->whereDate('tanggal_mulai', '<=', $tglSelesaiBaru)
            ->whereDate('tanggal_selesai', '>=', $tglMulaiBaru)
            ->orderBy('tanggal_mulai')
            ->get();

        $menitMulaiBaru   = $mulai->hour * 60 + $mulai->minute;
        $menitSelesaiBaru = $selesai->hour * 60 + $selesai->minute;

        $mulaiBaruDenganJeda   = $menitMulaiBaru - self::JEDA_MENIT;
        $selesaiBaruDenganJeda = $menitSelesaiBaru + self::JEDA_MENIT;

        foreach ($kandidat as $existing) {
            $existingMulai   = Carbon::parse($existing->tanggal_mulai);
            $existingSelesai = Carbon::parse($existing->tanggal_selesai);

            $menitMulaiExisting   = $existingMulai->hour * 60 + $existingMulai->minute;
            $menitSelesaiExisting = $existingSelesai->hour * 60 + $existingSelesai->minute;

            $tumpangTindihJam = $mulaiBaruDenganJeda < $menitSelesaiExisting
                              && $selesaiBaruDenganJeda > $menitMulaiExisting;

            if ($tumpangTindihJam) {
                return $existing;
            }
        }

        return null;
    }

    private function buatPesanBentrok(Carbon $mulai, Carbon $selesai, PeminjamanRuangan $bentrokDengan): string
    {
        $bentrokMulai   = Carbon::parse($bentrokDengan->tanggal_mulai);
        $bentrokSelesai = Carbon::parse($bentrokDengan->tanggal_selesai);

        $bedaHari = !$bentrokMulai->isSameDay($bentrokSelesai);

        if ($bedaHari) {
            return "Ruangan ini sudah dipakai tiap hari jam " .
                $bentrokMulai->format('H:i') . '–' . $bentrokSelesai->format('H:i') .
                ' pada rentang ' . $bentrokMulai->format('d/m/Y') . ' – ' . $bentrokSelesai->format('d/m/Y') .
                ". Jam yang kamu pilih tumpang tindih (perlu jeda min. " . self::JEDA_MENIT .
                " menit). Silakan pilih jam lain.";
        }

        return "Ruangan sudah digunakan pada jam ini (" .
            $bentrokMulai->format('d/m/Y H:i') . ' – ' . $bentrokSelesai->format('H:i') .
            "). Minimal jeda " . self::JEDA_MENIT . " menit dari peminjaman sebelumnya.";
    }

    public function store(Request $request)
    {

        $request->validate([
            'ruangan_id'         => 'required|exists:ruangan,id',
            'tanggal_mulai'      => 'required|date|after_or_equal:today',
            'tanggal_selesai'    => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai'          => 'required',
            'jam_selesai'        => 'required',
            'keperluan'          => 'required|string|max:500',
            'dokumen_pendukung'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'dokumen_pendukung.required'     => 'Dokumen pendukung wajib diunggah sebagai bukti kegiatan.',
            'dokumen_pendukung.mimes'        => 'Dokumen harus berformat PDF, JPG, atau PNG.',
            'dokumen_pendukung.max'          => 'Ukuran dokumen maksimal 5MB.',
        ]);

        $mulai   = Carbon::parse($request->tanggal_mulai . ' ' . $request->jam_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->jam_selesai);

        if ($selesai->lte($mulai)) {
            $pesan = $request->tanggal_mulai === $request->tanggal_selesai
                ? 'Jam selesai harus setelah jam mulai.'
                : 'Tanggal & jam selesai harus setelah tanggal & jam mulai.';
            return back()->withInput()->with('error', $pesan);
        }

        if ($mulai->diffInMinutes($selesai) < self::MIN_DURASI_MENIT) {
            return back()->withInput()->with('error',
                'Minimal durasi peminjaman ' . self::MIN_DURASI_MENIT . ' menit.');
        }

        $bentrokDengan = $this->cariBentrok($request->ruangan_id, $mulai, $selesai);

        if ($bentrokDengan) {
            $pesan = $this->buatPesanBentrok($mulai, $selesai, $bentrokDengan);
            return back()->withInput()->with('error', $pesan);
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen-peminjaman-ruangan', 'public');
        }

        $peminjaman = PeminjamanRuangan::create([
            'user_id'           => Auth::id(),
            'nama_ormawa'       => Auth::user()->organisasi,
            'ruangan_id'        => $request->ruangan_id,
            'tanggal_mulai'     => $mulai,
            'tanggal_selesai'   => $selesai,
            'keperluan'         => $request->keperluan,
            'dokumen_pendukung' => $dokumenPath,
            'status'            => 'menunggu_ketua',
        ]);

        $ketua = \App\Models\User::where('role', 'ketua')
            ->where('organisasi', Auth::user()->organisasi)
            ->first();

        if ($ketua) {
            $ketua->notify(
                new \App\Notifications\PengajuanRuanganNotification($peminjaman)
            );
        }

        return redirect()->route('anggota.riwayat-ruangan')
            ->with('success', 'Pengajuan berhasil dikirim. Menunggu persetujuan ketua ormawa.');
    }

    public function cekBentrok(Request $request)
    {
        $request->validate([
            'ruangan_id'      => 'required|exists:ruangan,id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required',
        ]);

        $mulai   = Carbon::parse($request->tanggal_mulai . ' ' . $request->jam_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->jam_selesai);

        if ($selesai->lte($mulai)) {
            return response()->json(['bentrok' => false]);
        }

        $bentrokDengan = $this->cariBentrok($request->ruangan_id, $mulai, $selesai);

        if (!$bentrokDengan) {
            return response()->json(['bentrok' => false]);
        }

        $pesan = $this->buatPesanBentrok($mulai, $selesai, $bentrokDengan);

        return response()->json(['bentrok' => true, 'pesan' => $pesan]);
    }

    public function cancel($id)
    {
        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_ketua')
            ->firstOrFail();

        if ($peminjaman->dokumen_pendukung) {
            Storage::disk('public')->delete($peminjaman->dokumen_pendukung);
        }

        $peminjaman->delete();

        return redirect()->route('anggota.riwayat-ruangan')
            ->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}