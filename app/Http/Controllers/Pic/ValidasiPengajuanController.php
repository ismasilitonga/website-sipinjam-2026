<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBarang;
use App\Models\PeminjamanRuangan;
use App\Notifications\PengajuanDisetujuiPicNotification;
use App\Notifications\PengajuanDitolakPicNotification;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ValidasiPengajuanController extends Controller
{
    public function index()
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])
            ->where('status', 'menunggu_pic')
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->latest()
            ->paginate(15);

        return view('PIC.daftar-pengajuan', compact('peminjaman_ruangans'));
    }

    public function setujui($id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $peminjaman = PeminjamanRuangan::whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
        ->findOrFail($id);

        $peminjaman->update(['status' => 'disetujui']);
        $peminjaman->user->notify(new PengajuanDisetujuiPicNotification($peminjaman));

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $peminjaman = PeminjamanRuangan::whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->findOrFail($id);

        $peminjaman->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);
        $peminjaman->user->notify(new PengajuanDitolakPicNotification($peminjaman));

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

       public function status()
{
    $lantai = (string) auth()->user()->lantai_pic;

    $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])
        ->whereIn('status', ['menunggu_ketua', 'menunggu_pic', 'ditolak'])
        ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
        ->latest()
        ->paginate(15);

    return view('PIC.status-peminjaman', compact('peminjaman_ruangans'));
}
         public function detail($id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $p = PeminjamanRuangan::with(['user', 'ruangan'])
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->findOrFail($id);

        return view('PIC.detail-peminjaman', compact('p'));
    }

        public function unduh()
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->latest()
            ->get();

        return view('PIC.unduh-laporan', compact('peminjaman_ruangans'));
    }
       public function exportExcel()
    {
        $lantai   = (string) auth()->user()->lantai_pic;
        $filename = 'laporan-ruangan-' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename={$filename}",
    ];

    $callback = function () use ($lantai) {
        $file = fopen('php://output', 'w');

        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($file, ['No', 'Peminjam', 'Ruangan', 'Tanggal', 'Waktu', 'Keperluan', 'Status']);

        $data = PeminjamanRuangan::with(['user', 'ruangan'])
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->latest()
            ->get();

        foreach ($data as $i => $item) {
            fputcsv($file, [
                $i + 1,
                $item->user->nama             ?? '-',
                $item->ruangan->nama_ruangan ?? '-',
                \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y'),
                \Carbon\Carbon::parse($item->tanggal_mulai)->format('H:i') . '-' . \Carbon\Carbon::parse($item->tanggal_selesai)->format('H:i'),
                $item->keperluan              ?? '-',
                ucfirst(str_replace('_', ' ', $item->status)),
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    public function exportPdf()
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $peminjaman_ruangans = PeminjamanRuangan::with(['user', 'ruangan'])
            ->whereHas('ruangan', fn($q) => $q->where('lantai', $lantai))
            ->latest()
            ->get();
        
            
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
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
    $peminjaman = PeminjamanBarang::with('barang')->findOrFail($id);

    if ($peminjaman->status !== 'menunggu_pic') {
        return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
    }
    $sudahDipinjam = PeminjamanBarang::where('barang_id', $peminjaman->barang_id)
        ->where('id', '!=', $peminjaman->id)
        ->where('status', 'disetujui')
        ->where('tanggal_pinjam', '<=', $peminjaman->tanggal_kembali_rencana)
        ->where('tanggal_kembali_rencana', '>=', $peminjaman->tanggal_pinjam)
        ->sum('jumlah');

    $stokTersedia = $peminjaman->barang->stok - $sudahDipinjam;

    if ($peminjaman->jumlah > $stokTersedia) {
        $peminjaman->update([
            'status'       => 'ditolak',
            'alasan_tolak' => 'Stok tidak mencukupi pada tanggal yang kamu pilih. Barang sudah dipinjam oleh peminjam lain pada tanggal yang sama.',
        ]);

        return back()->with('error',
            'Gagal menyetujui: stok ' . $peminjaman->barang->nama . 
            ' tidak mencukupi (tersedia ' . max(0, $stokTersedia) . ' dari ' . 
            $peminjaman->barang->stok . '). Pengajuan otomatis ditolak.'
        );
    }

    $peminjaman->update(['status' => 'disetujui']);

    return back()->with('success', 'Peminjaman barang berhasil disetujui.');
}

        public function tolakBarang($id)
    {
        $peminjaman_barang = PeminjamanBarang::findOrFail($id);
        $peminjaman_barang->update(['status' => 'ditolak']);

        return back()->with('success', 'Peminjaman barang berhasil ditolak.');
    }

        public function exportExcelBarang()
    {
        $filename = 'laporan-barang-' . date('Y-m-d') . '.csv';

        $headers = [
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
                    $item->user->nama ?? '-',
                    $item->barang->nama ?? '-',
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

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
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