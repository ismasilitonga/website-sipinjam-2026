<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\User;
use App\Notifications\PengajuanBarangNotification;
use Carbon\Carbon;

class PengajuanBarangController extends Controller
{
    const BUFFER_MENIT_BARANG = 15;

    private function bookingBentrok(int $barangId, Carbon $awal, Carbon $akhir, ?int $excludeId = null, bool $lock = false)
    {
        $awalCek  = $awal->copy()->subMinutes(self::BUFFER_MENIT_BARANG);
        $akhirCek = $akhir->copy()->addMinutes(self::BUFFER_MENIT_BARANG);

        $query = PeminjamanBarang::where('barang_id', $barangId)
            ->where('status', 'disetujui')
            ->where('tanggal_pinjam', '<', $akhirCek)
            ->where('tanggal_kembali_rencana', '>', $awalCek);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->get(['id', 'jumlah', 'tanggal_pinjam', 'tanggal_kembali_rencana']);
    }

    private function hitungStokTerpakai(int $barangId, Carbon $awal, Carbon $akhir, ?int $excludeId = null, bool $lock = false): int
    {
        return (int) $this->bookingBentrok($barangId, $awal, $akhir, $excludeId, $lock)->sum('jumlah');
    }

    public function index()
    {
        $now = now();

        $barangs = Barang::where('stok', '>', 0)
            ->where('jenis_barang', 'bisa_dipinjam')
            ->get()
            ->map(function ($b) use ($now) {
                $sudahDipinjam = $this->hitungStokTerpakai($b->id, $now, $now);
                $b->stok_tersedia = max(0, $b->stok - $sudahDipinjam);
                return $b;
            });

        return view('anggota.pengajuan-barang', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'               => 'required|exists:barang,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'jam_pinjam'              => 'required|date_format:H:i',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_kembali_rencana'     => 'required|date_format:H:i',
            'keperluan'               => 'required|string|max:500',
            'dokumen_pendukung'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'barang_id.required'                     => 'Barang harus dipilih.',
            'barang_id.exists'                       => 'Barang yang dipilih tidak valid.',
            'jumlah.required'                        => 'Jumlah harus diisi.',
            'jumlah.integer'                         => 'Jumlah harus berupa angka.',
            'jumlah.min'                              => 'Jumlah minimal adalah 1.',
            'tanggal_pinjam.required'                => 'Tanggal pinjam harus diisi.',
            'tanggal_pinjam.date'                    => 'Format tanggal pinjam tidak valid.',
            'tanggal_pinjam.after_or_equal'          => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'jam_pinjam.required'                    => 'Jam pinjam harus diisi.',
            'jam_pinjam.date_format'                 => 'Format jam pinjam tidak valid.',
            'tanggal_kembali_rencana.required'       => 'Rencana tanggal kembali harus diisi.',
            'tanggal_kembali_rencana.date'           => 'Format tanggal kembali tidak valid.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'jam_kembali_rencana.required'            => 'Jam kembali harus diisi.',
            'jam_kembali_rencana.date_format'         => 'Format jam kembali tidak valid.',
            'keperluan.required'                     => 'Keperluan harus diisi.',
            'keperluan.max'                          => 'Keperluan maksimal 500 karakter.',
            'dokumen_pendukung.required'             => 'Dokumen pendukung wajib diunggah.',
            'dokumen_pendukung.file'                  => 'Dokumen pendukung tidak valid.',
            'dokumen_pendukung.mimes'                 => 'Dokumen pendukung harus berformat PDF, JPG, JPEG, atau PNG.',
            'dokumen_pendukung.max'                   => 'Ukuran dokumen pendukung maksimal 5MB.',
        ]);

        $tanggalPinjamFull  = Carbon::parse($request->tanggal_pinjam . ' ' . $request->jam_pinjam);
        $tanggalKembaliFull = Carbon::parse($request->tanggal_kembali_rencana . ' ' . $request->jam_kembali_rencana);

        if ($tanggalKembaliFull->lte($tanggalPinjamFull)) {
            return back()->withInput()->with('error', 'Waktu kembali harus setelah waktu pinjam.');
        }
        $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen-pengajuan-barang', 'public');

        try {
            $peminjaman = DB::transaction(function () use ($request, $tanggalPinjamFull, $tanggalKembaliFull, $dokumenPath) {
                $barang = Barang::with('ruangan')->where('id', $request->barang_id)->lockForUpdate()->first();

                $sudahDipinjam = $this->hitungStokTerpakai(
                    $barang->id, $tanggalPinjamFull, $tanggalKembaliFull, null, true
                );
                $stokTersedia = $barang->stok - $sudahDipinjam;

                if ($request->jumlah > $stokTersedia) {
                    throw new \RuntimeException(
                        'Stok tidak mencukupi pada waktu yang kamu pilih. ' .
                        'Tersedia: ' . max(0, $stokTersedia) . ' ' . $barang->satuan .
                        ' (dari total ' . $barang->stok . ' ' . $barang->satuan . ') ' .
                        'untuk periode ' . $tanggalPinjamFull->translatedFormat('d F Y, H:i') . ' WIB' .
                        ' – ' . $tanggalKembaliFull->translatedFormat('d F Y, H:i') . ' WIB.'
                    );
                }

                $peminjaman = PeminjamanBarang::create([
                    'user_id'                 => Auth::id(),
                    'barang_id'               => $request->barang_id,
                    'nama_ormawa'             => Auth::user()->organisasi,
                    'jumlah'                  => $request->jumlah,
                    'tanggal_pinjam'          => $tanggalPinjamFull,
                    'tanggal_kembali_rencana' => $tanggalKembaliFull,
                    'keperluan'               => $request->keperluan,
                    'dokumen_pendukung'       => $dokumenPath,
                    'status'                  => 'menunggu_pic',
                ]);

                $peminjaman->setRelation('barang', $barang);

                return $peminjaman;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $barang = $peminjaman->barang;

        if ($barang->ruangan) {
            $pics = User::where('role', 'pic')
                ->where('lantai_pic', $barang->ruangan->lantai)
                ->get();
        } else {
            $pics = User::where('role', 'pic')->get();
        }

        foreach ($pics as $pic) {
            $pic->notify(new PengajuanBarangNotification($peminjaman));
        }

        return redirect()->route('anggota.riwayat-barang')
            ->with('success', 'Pengajuan barang berhasil dikirim. Menunggu persetujuan PIC.');
    }

    public function cekStok(Request $request)
    {
        $request->validate([
            'barang_id'               => 'required|exists:barang,id',
            'tanggal_pinjam'          => 'required|date',
            'jam_pinjam'              => 'nullable|date_format:H:i',
            'tanggal_kembali_rencana' => 'nullable|date',
            'jam_kembali_rencana'     => 'nullable|date_format:H:i',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        $jamPinjam              = $request->jam_pinjam ?: '00:00';
        $tanggalKembaliRencana  = $request->tanggal_kembali_rencana ?: $request->tanggal_pinjam;
        $jamKembali             = $request->jam_kembali_rencana ?: $jamPinjam;

        $tanggalPinjamFull  = Carbon::parse($request->tanggal_pinjam . ' ' . $jamPinjam);
        $tanggalKembaliFull = Carbon::parse($tanggalKembaliRencana . ' ' . $jamKembali);

        $bentrok       = $this->bookingBentrok($barang->id, $tanggalPinjamFull, $tanggalKembaliFull);
        $sudahDipinjam = (int) $bentrok->sum('jumlah');
        $stokTersedia  = max(0, $barang->stok - $sudahDipinjam);

        $bentrokMulai       = null;
        $bentrokSelesai     = null;
        $bolehPinjamMulai   = null;

        if ($stokTersedia <= 0 && $bentrok->isNotEmpty()) {
            $bentrokSelesaiRaw = Carbon::parse($bentrok->max('tanggal_kembali_rencana'));

            $bentrokMulai     = Carbon::parse($bentrok->min('tanggal_pinjam'))->translatedFormat('d F Y, H:i');
            $bentrokSelesai   = $bentrokSelesaiRaw->translatedFormat('d F Y, H:i');
            $bolehPinjamMulai = $bentrokSelesaiRaw->copy()
                ->addMinutes(self::BUFFER_MENIT_BARANG)
                ->translatedFormat('d F Y, H:i');
        }

        return response()->json([
            'stok_total'         => $barang->stok,
            'stok_tersedia'      => $stokTersedia,
            'satuan'             => $barang->satuan,
            'tersedia'           => $stokTersedia > 0,
            'bentrok_mulai'      => $bentrokMulai,
            'bentrok_selesai'    => $bentrokSelesai,
            'boleh_pinjam_mulai' => $bolehPinjamMulai,
            'buffer_menit'       => self::BUFFER_MENIT_BARANG,
            'estimasi_awal'      => empty($request->tanggal_kembali_rencana),
        ]);
    }
}