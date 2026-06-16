<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanBarang;
use App\Notifications\PengajuanDisetujuiPicNotification;
use App\Notifications\PengajuanDitolakPicNotification;

class ValidasiPengajuanController extends Controller
{
    public function index()
{
    $lantai = (string) auth()->user()->lantai_pic;

    $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])
        ->where('status', 'menunggu_pic')
        ->whereHas('ruangan', function ($q) use ($lantai) {
            $q->where('lantai', $lantai);
        })
        ->latest()
        ->paginate(15);

    return view('PIC.daftar-pengajuan', compact('peminjaman_ruangans'));
}

public function setujui($id)
{
   $lantai = (string) auth()->user()->lantai_pic;

    $peminjaman_ruangans = PeminjamanRuangan::whereHas('ruangan', function ($q) use ($lantai) {
        $q->where('lantai', $lantai);
    })->findOrFail($id);

    $peminjaman_ruangans->update(['status' => 'disetujui']);
    $peminjaman_ruangans->user->notify(new PengajuanDisetujuiPicNotification($peminjaman_ruangans));
    return back()->with('success', 'Pengajuan berhasil disetujui.');
}

public function tolak(Request $request, $id)
{
    /** @var \App\Models\User $user */
$lantai = (string) auth()->user()->lantai_pic;

    $peminjaman_ruangans = PeminjamanRuangan::whereHas('ruangan', function ($q) use ($lantai) {
        $q->where('lantai', $lantai);
    })->findOrFail($id);

    $peminjaman_ruangans->update(['status' => 'ditolak']);
    $peminjaman_ruangans->user->notify(new PengajuanDitolakPicNotification($peminjaman_ruangans));
    return back()->with('success', 'Pengajuan berhasil ditolak.');
}

    public function status()
    {
        $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])->latest()->paginate(15);
        return view('PIC.status-peminjaman', compact('peminjaman_ruangans'));
    }

    public function unduh()
    {
        $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])->latest()->get();
        return view('PIC.unduh-laporan', compact('peminjaman_ruangans'));
    }

    public function exportExcel()
    {
        $filename = 'laporan-ruangan-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Peminjam', 'Ruangan', 'Tanggal', 'Status']);

            $data = PeminjamanRuangan::with(['user', 'ruangan'])->latest()->get();
            foreach ($data as $i => $item) {
                fputcsv($file, [
                    $i + 1,
                    $item->user->name    ?? '-',
                    $item->ruangan->nama ?? '-',
                    $item->tanggal_pinjam,
                    ucfirst(str_replace('_', ' ', $item->status)),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])->latest()->get();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $html   = view('PIC.laporan-pdf', compact('peminjaman_ruangans'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-ruangan-' . date('Y-m-d') . '.pdf"',
        ]);
    }

    public function setujuiBarang($id)
{
    $peminjaman_barang = PeminjamanBarang::findOrFail($id);

    $peminjaman_barang->update([
        'status' => 'disetujui'
    ]);

    return back()->with(
        'success',
        'Peminjaman barang berhasil disetujui.'
    );
}

public function tolakBarang($id)
{
    $peminjaman_barang = PeminjamanBarang::findOrFail($id);

    $peminjaman_barang->update([
        'status' => 'ditolak'
    ]);

    return back()->with(
        'success',
        'Peminjaman barang berhasil ditolak.'
    );
}

    public function exportExcelBarang()
    {
        $filename = 'laporan-barang-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Peminjam', 'Barang', 'Jumlah', 'Tanggal Pinjam', 'Tanggal Kembali', 'Status']);

            $data = PeminjamanBarang::with(['user', 'barang'])->latest()->get();
            foreach ($data as $i => $item) {
                fputcsv($file, [
                    $i + 1,
                    $item->user->name       ?? '-',
                    $item->barang->nama     ?? '-',
                    $item->jumlah,
                    $item->tanggal_pinjam,
                    $item->tanggal_kembali_rencana,
                    ucfirst(str_replace('_', ' ', $item->status)),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdfBarang()
    {
        $peminjaman_barangs= PeminjamanBarang::with(['user', 'barang'])->latest()->get();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $html   = view('PIC.laporan-barang-pdf', compact('peminjaman_barangs'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-barang-' . date('Y-m-d') . '.pdf"',
        ]);
    }
}