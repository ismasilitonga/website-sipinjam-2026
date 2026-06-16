<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Insiden;

class LaporInsidenController extends Controller
{
    public function index()
    {
        return view('anggota.lapor-insiden');
    }

    /** Kirim laporan insiden (FR-08) */
    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'lokasi'      => 'required|string|max:255',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('insiden', 'public')
            : null;

        Insiden::create([
            'user_id'    => Auth::id(),
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'lokasi'     => $request->lokasi,
            'foto'       => $fotoPath,
            'status'     => 'dilaporkan',
        ]);

        return redirect()->route('anggota.riwayat-insiden')
            ->with('success', 'Laporan insiden berhasil dikirim.');
    }
}
