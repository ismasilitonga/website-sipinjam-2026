<?php
namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class DaftarBarangController extends Controller
{
   public function index()
{
    $barangs = Barang::where(function ($q) {
            $q->where('jenis_barang', 'bisa_dipinjam')
              ->orWhereNull('jenis_barang');
        })
        ->latest()
        ->paginate(12);

    return view('shared.daftar-barang', compact('barangs'));
}
}