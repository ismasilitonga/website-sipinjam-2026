<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanBarang;
use App\Models\Barang;

class PengajuanBarangController extends Controller
{
    public function index()
{
    $barangs = Barang::where('stok', '>', 0)
        ->where('jenis_barang', 'bisa_dipinjam')
        ->get()
        ->map(function ($b) {
            $sudahDipinjam = PeminjamanBarang::where('barang_id', $b->id)
                ->whereIn('status', ['menunggu_pic', 'disetujui'])
                ->where('tanggal_pinjam', '<=', now())
                ->where('tanggal_kembali_rencana', '>=', now())
                ->sum('jumlah');

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
        'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        'keperluan'               => 'required|string|max:500',
        'dokumen_pendukung'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ], [
        'barang_id.required'                     => 'Barang harus dipilih.',
        'barang_id.exists'                       => 'Barang yang dipilih tidak valid.',
        'jumlah.required'                        => 'Jumlah harus diisi.',
        'jumlah.integer'                         => 'Jumlah harus berupa angka.',
        'jumlah.min'                             => 'Jumlah minimal adalah 1.',
        'tanggal_pinjam.required'                => 'Tanggal pinjam harus diisi.',
        'tanggal_pinjam.date'                    => 'Format tanggal pinjam tidak valid.',
        'tanggal_pinjam.after_or_equal'          => 'Tanggal pinjam tidak boleh sebelum hari ini.',
        'tanggal_kembali_rencana.required'       => 'Rencana tanggal kembali harus diisi.',
        'tanggal_kembali_rencana.date'           => 'Format tanggal kembali tidak valid.',
        'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        'keperluan.required'                     => 'Keperluan harus diisi.',
        'keperluan.max'                          => 'Keperluan maksimal 500 karakter.',
        'dokumen_pendukung.required'             => 'Dokumen pendukung wajib diunggah.',
        'dokumen_pendukung.file'                 => 'Dokumen pendukung tidak valid.',
        'dokumen_pendukung.mimes'                => 'Dokumen pendukung harus berformat PDF, JPG, JPEG, atau PNG.',
        'dokumen_pendukung.max'                  => 'Ukuran dokumen pendukung maksimal 5MB.',
    ]);

    $barang = Barang::findOrFail($request->barang_id);

    $sudahDipinjam = PeminjamanBarang::where('barang_id', $request->barang_id)
        ->whereIn('status', ['menunggu_pic', 'disetujui'])
        ->where('tanggal_pinjam', '<=', $request->tanggal_kembali_rencana)
        ->where('tanggal_kembali_rencana', '>=', $request->tanggal_pinjam)
        ->sum('jumlah');

    $stokTersedia = $barang->stok - $sudahDipinjam;

    if ($request->jumlah > $stokTersedia) {
        return back()->withInput()->with('error',
            'Stok tidak mencukupi pada tanggal yang kamu pilih. ' .
            'Tersedia: ' . max(0, $stokTersedia) . ' ' . $barang->satuan . 
            ' (dari total ' . $barang->stok . ' ' . $barang->satuan . ').'
        );
    }

    $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen-pengajuan-barang', 'public');

    PeminjamanBarang::create([
        'user_id'                 => Auth::id(),
        'barang_id'               => $request->barang_id,
        'nama_ormawa'             => Auth::user()->organisasi,
        'jumlah'                  => $request->jumlah,
        'tanggal_pinjam'          => $request->tanggal_pinjam,
        'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
        'keperluan'               => $request->keperluan,
        'dokumen_pendukung'       => $dokumenPath,
        'status'                  => 'menunggu_pic',
    ]);

    return redirect()->route('anggota.riwayat-barang')
        ->with('success', 'Pengajuan barang berhasil dikirim. Menunggu persetujuan PIC.');
}
    public function cekStok(Request $request)
    {
    $request->validate([
        'barang_id'               => 'required|exists:barang,id',
        'tanggal_pinjam'          => 'required|date',
        'tanggal_kembali_rencana' => 'required|date',
    ]);

    $barang = Barang::findOrFail($request->barang_id);

    $sudahDipinjam = PeminjamanBarang::where('barang_id', $request->barang_id)
        ->whereIn('status', ['menunggu_pic', 'disetujui'])
        ->where('tanggal_pinjam', '<=', $request->tanggal_kembali_rencana)
        ->where('tanggal_kembali_rencana', '>=', $request->tanggal_pinjam)
        ->sum('jumlah');

    $stokTersedia = max(0, $barang->stok - $sudahDipinjam);

    return response()->json([
        'stok_total'    => $barang->stok,
        'stok_tersedia' => $stokTersedia,
        'satuan'        => $barang->satuan,
        'tersedia'      => $stokTersedia > 0,
    ]);
}
}