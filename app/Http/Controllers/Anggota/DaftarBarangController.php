<?php
namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class DaftarBarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest()->paginate(12);
        return view('shared.daftar-barang', compact('barangs'));
    }
}
