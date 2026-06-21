<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanBarang;

class ValidasiPendaftarController extends Controller
{
  public function index()
    {
    $pendaftars = User::where('status', 'pending')
        ->whereIn('role', ['anggota', 'ketua'])
        ->latest()
        ->paginate(15);

        return view('admin.pendaftar', compact('pendaftars'));
    }
        public function setujui($id)
    {
        User::findOrFail($id)->update(['status' => 'aktif']);
        return back()->with('success', 'Akun berhasil diaktifkan.');
    }
        public function tolak($id)
    {
        User::findOrFail($id)->update(['status' => 'ditolak']);
        return back()->with('success', 'Pendaftar ditolak.');
    }
        public function unduhLaporan()
    {
        $peminjamanRuangans = PeminjamanRuangan::with(['user', 'ruangan'])->latest()->get();
        return view('admin.unduh-laporan', compact('peminjamanRuangans'));
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
                    $item->user->nama    ?? '-',
                    $item->ruangan->nama_ruangan ?? '-',
                    $item->tanggal_mulai,
                    ucfirst(str_replace('_', ' ', $item->status)),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $peminjaman_ruangan = PeminjamanRuangan::with(['user', 'ruangan'])->latest()->get();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $html   = view('admin.laporan-pdf', compact('peminjaman_ruangan'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-ruangan-' . date('Y-m-d') . '.pdf"',
        ]);
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
                    $item->user->nama       ?? '-',
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
        $peminjaman_barangs = PeminjamanBarang::with(['user', 'barang'])->latest()->get();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $html   = view('admin.laporan-barang-pdf', compact('peminjaman_barangs'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-barang-' . date('Y-m-d') . '.pdf"',
        ]);
    }
}