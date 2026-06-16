<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;

class DetailRuanganController extends Controller
{
    public function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('anggota.detail-ruangan', compact('ruangan'));
    }
}
