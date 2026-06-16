<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;

class DaftarRuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('lantai', 'asc')
            ->orderBy('nama_ruangan', 'asc')
            ->paginate(11);

        return view('shared.daftar-ruangan', compact('ruangans'));
    }
}