<?php
namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengalihanBarang;
use App\Models\PeminjamanBarang;
use App\Models\User;

class PengalihanBarangController extends Controller
{
    public function index()
    {
        $peminjamanAktif = PeminjamanBarang::with('barang')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->whereNotNull('waktu_diserahkan')
            ->whereNull('waktu_diterima_kembali')
            ->get();

        $pengalihanMasuk = PengalihanBarang::with(['peminjamanBarang.barang', 'dariUser'])
            ->where('ke_user_id', Auth::id())
            ->where('status', 'menunggu')
            ->get();

        $pengalihanKeluar = PengalihanBarang::with([
            'peminjamanBarang.barang',
            'keUser'
        ])
            ->where('dari_user_id', Auth::id())
            ->where('status', 'menunggu')
            ->first();

        return view(
            'anggota.pengalihan-barang',
            compact('peminjamanAktif', 'pengalihanMasuk', 'pengalihanKeluar')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_barang_id' => 'required|exists:peminjaman_barang,id',
            'ke_user_id'           => 'required',
            'alasan'               => 'required|string|max:500',
        ]);

        $penerima = User::where('nim', $request->ke_user_id)
            ->where('status', 'aktif')
            ->first();

        if (!$penerima) {
            return back()->withErrors([
                'ke_user_id' => 'Data pengguna tidak tersedia. Pastikan NIM benar dan akun penerima sudah disetujui admin.'
            ])->withInput();
        }

        if (in_array($penerima->role, ['admin', 'pic', 'pamdal'])) {
            return back()->withErrors([
                'ke_user_id' => 'Penerima harus sesama anggota/ketua ormawa, bukan admin/PIC/pamdal.'
            ])->withInput();
        }

        if ($penerima->id == Auth::id()) {
            return back()->withErrors([
                'ke_user_id' => 'Tidak dapat mengalihkan ke diri sendiri.'
            ])->withInput();
        }

        $peminjaman = PeminjamanBarang::where('id', $request->peminjaman_barang_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $sudahAda = PengalihanBarang::where('peminjaman_barang_id', $peminjaman->id)
            ->where('status', 'menunggu')
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Sudah ada pengajuan pengalihan yang sedang menunggu konfirmasi.');
        }

        PengalihanBarang::create([
            'peminjaman_barang_id' => $peminjaman->id,
            'dari_user_id'         => Auth::id(),
            'ke_user_id'           => $penerima->id,
            'alasan'               => $request->alasan,
            'status'               => 'menunggu',
        ]);

        return back()->with('success', 'Pengajuan pengalihan berhasil dikirim. Menunggu konfirmasi penerima.');
    }

    public function konfirmasi(Request $request, $id)
    {
        $request->validate(['aksi' => 'required|in:terima,tolak']);

        $pengalihan = PengalihanBarang::where('id', $id)
            ->where('ke_user_id', Auth::id())
            ->where('status', 'menunggu')
            ->firstOrFail();

        if ($request->aksi === 'terima') {
            $pengalihan->peminjamanBarang->update(['user_id' => Auth::id()]);
            $pengalihan->update([
                'status'             => 'dikonfirmasi',
                'waktu_dikonfirmasi' => now(),
            ]);
            return back()->with('success', 'Pengalihan barang berhasil diterima.');
        }

        $pengalihan->update(['status' => 'ditolak']);
        return back()->with('success', 'Pengalihan barang ditolak.');
    }
}