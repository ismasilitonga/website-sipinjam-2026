<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request; 

class RiwayatRuanganAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            ''                 => 'Semua',
            'menunggu_ketua'   => 'Menunggu Ketua',
            'menunggu_pic'     => 'Menunggu PIC',
            'disetujui'        => 'Disetujui',
            'ditolak'          => 'Ditolak',
            'berjalan'         => 'Berjalan',
            'selesai'          => 'Selesai',
        ];

        $riwayat = PeminjamanRuangan::with('ruangan')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('anggota.riwayat-ruangan', compact('riwayat', 'filters'));
    }

    public function edit($id)
    {
        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_ketua')
            ->firstOrFail();

        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('anggota.edit-pengajuan', compact('peminjaman', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangan_id'        => 'required|exists:ruangan,id',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'keperluan'         => 'required|string|max:255',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $peminjaman = PeminjamanRuangan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_ketua')
            ->firstOrFail();

        $data = $request->only(['ruangan_id', 'tanggal_mulai', 'tanggal_selesai', 'keperluan']);

        if ($request->hasFile('dokumen_pendukung')) {
            if ($peminjaman->dokumen_pendukung) {
                Storage::disk('public')->delete($peminjaman->dokumen_pendukung);
            }
            $data['dokumen_pendukung'] = $request->file('dokumen_pendukung')->store('dokumen-pengajuan', 'public');
        }

        $peminjaman->update($data);

        return redirect()
            ->route('anggota.riwayat-ruangan')
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }
}