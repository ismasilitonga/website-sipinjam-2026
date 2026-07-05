<?php

namespace App\Http\Controllers\Pamdal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;

class KonfirmasiKunciController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = PeminjamanRuangan::with(['user', 'ruangan'])
            ->whereIn('status', ['disetujui', 'berjalan' , 'selesai'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_ormawa', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('ruangan', function ($ruangan) use ($search) {
                            $ruangan->where('nama_ruangan', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            return response()->json(
                $query->get()->map(fn($p) => [
                    'id' => $p->id,
                    'user_nama' => $p->user->nama ?? '-',
                    'nama_ormawa' => $p->nama_ormawa,
                    'ruangan_nama' => $p->ruangan->nama_ruangan ?? '-',
                    'tanggal_mulai' => \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y'),
                    'jam_mulai' => \Carbon\Carbon::parse($p->tanggal_mulai)->format('H:i'),
                    'jam_selesai' => \Carbon\Carbon::parse($p->tanggal_selesai)->format('H:i'),
                    'waktu_kunci_diambil' => $p->waktu_kunci_diambil ? \Carbon\Carbon::parse($p->waktu_kunci_diambil)->format('H:i') : null,
                    'waktu_kunci_dikembalikan' => $p->waktu_kunci_dikembalikan ? \Carbon\Carbon::parse($p->waktu_kunci_dikembalikan)->format('H:i') : null,
                ])
            );
        }

        $peminjaman_ruangans = $query->paginate(15)->withQueryString();

        return view('pamdal.daftar-peminjaman', compact('peminjaman_ruangans', 'search'));
    }

    public function konfirmasiAmbil($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $peminjaman->update([
            'waktu_kunci_diambil' => now(),
        ]);

        return back()->with('success', 'Pengambilan kunci berhasil dikonfirmasi.');
    }

    public function konfirmasiKembali($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $peminjaman->update([
            'waktu_kunci_dikembalikan' => now(),
        ]);

        return back()->with('success', 'Pengembalian kunci berhasil dikonfirmasi.');
    }
}