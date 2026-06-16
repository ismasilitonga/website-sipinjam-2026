<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PeminjamanBarang;
use App\Models\Barang;

class PengajuanBarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        return view('anggota.pengajuan-barang', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'               => 'required|exists:barang,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            // FIX: after → after_or_equal agar boleh kembali di hari yang sama dengan pinjam
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan'               => 'required|string|max:500',
        ], [
            'barang_id.required'                      => 'Barang harus dipilih.',
            'barang_id.exists'                        => 'Barang yang dipilih tidak valid.',
            'jumlah.required'                         => 'Jumlah harus diisi.',
            'jumlah.integer'                          => 'Jumlah harus berupa angka.',
            'jumlah.min'                              => 'Jumlah minimal adalah 1.',
            'tanggal_pinjam.required'                 => 'Tanggal pinjam harus diisi.',
            'tanggal_pinjam.date'                     => 'Format tanggal pinjam tidak valid.',
            'tanggal_pinjam.after_or_equal'           => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'tanggal_kembali_rencana.required'        => 'Rencana tanggal kembali harus diisi.',
            'tanggal_kembali_rencana.date'            => 'Format tanggal kembali tidak valid.',
            'tanggal_kembali_rencana.after_or_equal'  => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'keperluan.required'                      => 'Keperluan harus diisi.',
            'keperluan.max'                           => 'Keperluan maksimal 500 karakter.',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($request->jumlah > $barang->stok) {
            return back()->withInput()->with('error', 'Jumlah yang diminta melebihi stok tersedia (' . $barang->stok . ' ' . $barang->satuan . ').');
        }

        PeminjamanBarang::create([
            'user_id'                 => Auth::id(),
            'barang_id'               => $request->barang_id,
            'nama_ormawa'             => Auth::user()->organisasi,
            'jumlah'                  => $request->jumlah,
            'tanggal_pinjam'          => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'keperluan'               => $request->keperluan,
            'status'                  => 'menunggu_pic',
        ]);

        return redirect()->route('anggota.riwayat-barang')
            ->with('success', 'Pengajuan barang berhasil dikirim. Menunggu persetujuan PIC.');
    }
}