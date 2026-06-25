<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanRuangan;
use App\Models\User;
use App\Notifications\PengajuanDisetujuiKetuaNotification;
use App\Notifications\PengajuanDitolakKetuaNotification;

class DaftarPengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = PeminjamanRuangan::with(['user', 'ruangan'])
            ->where('nama_ormawa', Auth::user()->organisasi)
            ->where('status', 'menunggu_ketua')
            ->latest()
            ->paginate(10);

        return view('ketua.daftar-pengajuan', compact('pengajuans'));
    }

    public function setujui(Request $request, $id)
    {
        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('nama_ormawa', Auth::user()->organisasi)
            ->firstOrFail();

        $peminjaman->update(['status' => 'menunggu_pic']);
        $lantai = (string) $peminjaman->ruangan->lantai;

        $pic = User::where('role', 'pic')
                   ->whereRaw('CAST(lantai_pic AS CHAR) = ?', [$lantai])
                   ->first();

        if ($pic) {
            $pic->notify(new PengajuanDisetujuiKetuaNotification($peminjaman));
        }

        return back()->with('success', 'Pengajuan disetujui dan diteruskan ke PIC.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan_tolak' => 'required|string|max:500']);
        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('nama_ormawa', Auth::user()->organisasi)
            ->firstOrFail();

        $peminjaman->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        $peminjaman->user->notify(new PengajuanDitolakKetuaNotification($peminjaman));

        return back()->with('success', 'Pengajuan ditolak.');
    }
}