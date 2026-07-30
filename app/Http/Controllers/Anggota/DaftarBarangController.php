<?php
namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;

class DaftarBarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $barangs = Barang::where(function ($q) {
                $q->where('jenis_barang', 'bisa_dipinjam')
                  ->orWhereNull('jenis_barang');
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('kategori', 'like', "%{$search}%")
                      ->orWhere('organisasi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
            
        $awalHariIni  = now()->startOfDay();
        $akhirHariIni = now()->endOfDay();

        $barangs->getCollection()->transform(function ($b) use ($awalHariIni, $akhirHariIni) {
            $sudahDipinjam = PeminjamanBarang::where('barang_id', $b->id)
                ->where('status', 'disetujui')
                ->whereNull('waktu_diterima_kembali')
                ->where('tanggal_pinjam', '<', $akhirHariIni)
                ->where('tanggal_kembali_rencana', '>', $awalHariIni)
                ->sum('jumlah');

            $b->stok_tersedia = max(0, $b->stok - $sudahDipinjam);
            return $b;
        });

        return view('shared.daftar-barang', compact('barangs'));
    }
}