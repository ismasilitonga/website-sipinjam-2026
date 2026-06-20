<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;

class PengajuanRuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::where('status', 'tersedia')->get();
        return view('anggota.pengajuan-ruangan', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'ruangan_id'         => 'required|exists:ruangan,id',
        'tanggal_penggunaan' => 'required|date|after_or_equal:today',
        'jam_mulai'          => 'required',
        'jam_selesai'        => 'required|after:jam_mulai',
        'keperluan'          => 'required|string|max:500',
    ],       [
        'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
    ]);

        $bentrok = PeminjamanRuangan::where('ruangan_id', $request->ruangan_id)
            ->where('status', '!=', 'ditolak')
            ->where(function ($q) use ($request) {
                $mulai   = $request->tanggal_penggunaan . ' ' . $request->jam_mulai;
                $selesai = $request->tanggal_penggunaan . ' ' . $request->jam_selesai;
                $q->whereBetween('tanggal_mulai', [$mulai, $selesai])
                  ->orWhereBetween('tanggal_selesai', [$mulai, $selesai]);
            })->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Ruangan sudah dipesan pada waktu tersebut.');
        }

    $ruangan = PeminjamanRuangan::create([
    'user_id'          => Auth::id(),
    'nama_ormawa'      => Auth::user()->organisasi,
    'ruangan_id'       => $request->ruangan_id,
    'tanggal_mulai'    => $request->tanggal_penggunaan . ' ' . $request->jam_mulai,
    'tanggal_selesai'  => $request->tanggal_penggunaan . ' ' . $request->jam_selesai,
    'keperluan'        => $request->keperluan,
    'status'           => 'menunggu_ketua',
    'status_pemakaian' => 'booked',
]);

$ketua = \App\Models\User::where('role', 'ketua')
    ->where('organisasi', Auth::user()->organisasi)
    ->first();
    
if ($ketua) {
    $ketua->notify(
        new \App\Notifications\PengajuanRuanganNotification($ruangan)
    );
}

return redirect()->route('anggota.riwayat-ruangan')
    ->with('success', 'Pengajuan berhasil dikirim. Menunggu persetujuan ketua ormawa.');
    }

public function cancel($id)
{
    $peminjaman = PeminjamanRuangan::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'menunggu_ketua')
        ->firstOrFail();

    $peminjaman->delete();

    return redirect()->route('anggota.riwayat-ruangan')
        ->with('success', 'Pengajuan berhasil dibatalkan.');
}
}