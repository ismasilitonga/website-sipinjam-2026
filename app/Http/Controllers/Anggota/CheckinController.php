<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\CheckIn;

class CheckinController extends Controller
{
    public function index()
    {
        $peminjaman_ruangan = PeminjamanRuangan::with('ruangan')
            ->where('user_id', Auth::id())
            ->where('status', 'disetujui')
            ->whereDoesntHave('checkIn') 
            ->whereDate('tanggal_mulai', today())
            ->get();

        return view('anggota.checkin', compact('peminjaman_ruangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman_ruangan,id',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $peminjaman = PeminjamanRuangan::where('id', $request->peminjaman_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $path = $request->file('foto_ktp')->store('ktp', 'public');

        CheckIn::create([
            'peminjaman_id' => $peminjaman->id,
            'foto_ktp' => $path,
            'waktu_checkin' => now(),
            'status_kunci' => 'diambil',
        ]);

      
        $peminjaman->update(['status' => 'berjalan']);

        return redirect()->back()->with('success', 'Check-in berhasil!');
    }
}