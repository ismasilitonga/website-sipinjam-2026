<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiwayatRuanganAnggotaController extends Controller
{
    const JEDA_MENIT = 30;

    const MIN_DURASI_MENIT = 60;

    const MAKS_DURASI_HARI = 3;

    const STATUS_MENGUNCI = ['disetujui', 'berjalan'];

    public function index(Request $request)
    {
        $filters = [
            ''                 => 'Semua',
            'menunggu_ketua'   => 'Menunggu Ketua',
            'menunggu_pic'     => 'Menunggu PIC',
            'disetujui'        => 'Disetujui',
            'ditolak'          => 'Ditolak',
            'berjalan'         => 'Berjalan',
            'selesai'          => 'Selesai',
        ];

        $riwayat = PeminjamanRuangan::with(['ruangan', 'checkInHariIni'])
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('anggota.riwayat-ruangan', compact('riwayat', 'filters'));
    }

    public function show($id)
    {
        $peminjaman = PeminjamanRuangan::with('ruangan')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('anggota.riwayat-ruangan-detail', compact('peminjaman'));
    }

    public function edit($id)
    {
        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_ketua')
            ->firstOrFail();

        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('anggota.edit-pengajuan', compact('peminjaman', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangan_id'        => 'required|exists:ruangan,id',
            'tanggal_mulai'     => 'required|date|after_or_equal:today',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'keperluan'         => 'required|string|max:255',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_ketua')
            ->firstOrFail();

        $mulai   = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);

        if ($selesai->lte($mulai)) {
            return back()->withInput()->with('error', 'Tanggal & jam selesai harus setelah tanggal & jam mulai.');
        }

        if ($mulai->diffInMinutes($selesai) < self::MIN_DURASI_MENIT) {
            return back()->withInput()->with('error',
                'Minimal durasi peminjaman ' . self::MIN_DURASI_MENIT . ' menit.');
        }

        $batasTanggal = $mulai->copy()->startOfDay()->addDays(self::MAKS_DURASI_HARI - 1)->endOfDay();

        if ($selesai->gt($batasTanggal)) {
            return back()->withInput()->with('error',
                'Peminjaman ruangan maksimal ' . self::MAKS_DURASI_HARI . ' hari.');
        }

        $bentrokDengan = $this->cariBentrokMultiHari($request->ruangan_id, $peminjaman->id, $mulai, $selesai);

        if ($bentrokDengan) {
            return back()->withInput()->with('error', $this->pesanBentrok($bentrokDengan));
        }

        $data = $request->only(['ruangan_id', 'keperluan']);
        $data['tanggal_mulai']   = $mulai;
        $data['tanggal_selesai'] = $selesai;

        if ($request->hasFile('dokumen_pendukung')) {
            if ($peminjaman->dokumen_pendukung) {
                Storage::disk('public')->delete($peminjaman->dokumen_pendukung);
            }
            $data['dokumen_pendukung'] = $request->file('dokumen_pendukung')->store('dokumen-pengajuan', 'public');
        }

        $peminjaman->update($data);

        return redirect()
            ->route('anggota.riwayat-ruangan')
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    private function cariBentrokMultiHari($ruanganId, $peminjamanId, Carbon $mulai, Carbon $selesai)
    {
        $tglMulaiBaru   = $mulai->copy()->startOfDay();
        $tglSelesaiBaru = $selesai->copy()->startOfDay();

        $kandidat = PeminjamanRuangan::where('ruangan_id', $ruanganId)
            ->where('id', '!=', $peminjamanId)
            ->whereIn('status', self::STATUS_MENGUNCI)
            ->whereDate('tanggal_mulai', '<=', $tglSelesaiBaru)
            ->whereDate('tanggal_selesai', '>=', $tglMulaiBaru)
            ->orderBy('tanggal_mulai')
            ->get();

        if ($kandidat->isEmpty()) {
            return null;
        }

        $jamMulaiBaru   = $mulai->hour * 60 + $mulai->minute - self::JEDA_MENIT;
        $jamSelesaiBaru = $selesai->hour * 60 + $selesai->minute + self::JEDA_MENIT;

        foreach ($kandidat as $item) {
            $itemMulai   = Carbon::parse($item->tanggal_mulai);
            $itemSelesai = Carbon::parse($item->tanggal_selesai);

            $jamMulaiItem   = $itemMulai->hour * 60 + $itemMulai->minute;
            $jamSelesaiItem = $itemSelesai->hour * 60 + $itemSelesai->minute;

            $jamBeririsan = $jamMulaiBaru < $jamSelesaiItem && $jamSelesaiBaru > $jamMulaiItem;

            if ($jamBeririsan) {
                return $item;
            }
        }

        return null;
    }

    private function pesanBentrok(PeminjamanRuangan $bentrokDengan): string
    {
        $itemMulai   = Carbon::parse($bentrokDengan->tanggal_mulai);
        $itemSelesai = Carbon::parse($bentrokDengan->tanggal_selesai);

        return "Ruangan sudah dipesan pihak lain pada jam " .
            $itemMulai->format('H:i') . '–' . $itemSelesai->format('H:i') .
            " (berlaku tiap hari selama " . $itemMulai->format('d/m/Y') . ' – ' . $itemSelesai->format('d/m/Y') .
            "). Minimal jeda " . self::JEDA_MENIT . " menit dari jam tersebut. Silakan pilih jam lain.";
    }
}