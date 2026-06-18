<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class RiwayatRuanganAdminController extends Controller
{
    public function index(Request $request)
{
    $ruangans = Ruangan::orderBy('lantai')
        ->orderBy('nama_ruangan')
        ->get();

    $query = PeminjamanRuangan::with(['user', 'ruangan']);

    if ($request->filled('ruangan_id')) {
        $query->where('ruangan_id', $request->ruangan_id);
    }

    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal_mulai', $request->tanggal);
    }

    if ($request->filled('minggu') && ! $request->filled('tanggal')) {
        [$tahun, $minggu] = explode('-W', $request->minggu);

        $awalMinggu  = Carbon::now()->setISODate((int) $tahun, (int) $minggu)->startOfWeek();
        $akhirMinggu = Carbon::now()->setISODate((int) $tahun, (int) $minggu)->endOfWeek();

        $query->whereBetween('tanggal_mulai', [
            $awalMinggu->toDateString(),
            $akhirMinggu->toDateString(),
        ]);
    }

    if ($request->filled('bulan') && ! $request->filled('tanggal')) {
        $query->whereYear('tanggal_mulai', substr($request->bulan, 0, 4))
              ->whereMonth('tanggal_mulai', substr($request->bulan, 5, 2));
    }

    $riwayat = $query->latest('tanggal_mulai')
        ->paginate(15)
        ->withQueryString();

    $totalSelesai = (clone $query)
        ->where('status', 'selesai')
        ->count();

    $totalDisetujui = (clone $query)
        ->where('status', 'disetujui')
        ->count();

    return view('shared.riwayat-ruangan', compact(
        'riwayat',
        'ruangans',
        'totalSelesai',
        'totalDisetujui'
    ));
}

   public function detail($id)
{
    $p = PeminjamanRuangan::with(['user', 'ruangan'])
        ->findOrFail($id);

    return view('admin.detail-peminjaman', compact('p'));
}
 public function export(Request $request)
{
    $ruangans = Ruangan::orderBy('lantai')->orderBy('nama_ruangan')->get();

    $query = PeminjamanRuangan::with(['user', 'ruangan'])
        ->whereIn('status', ['disetujui', 'selesai']);
        // Hapus ->whereHas('ruangan', ...) karena admin tidak perlu filter lantai

    if ($request->filled('ruangan_id')) {
        $query->where('ruangan_id', $request->ruangan_id);
    }

    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal_mulai', $request->tanggal);
    }

    if ($request->filled('minggu') && ! $request->filled('tanggal')) {
        [$tahun, $minggu] = explode('-W', $request->minggu);

        $awalMinggu  = Carbon::now()->setISODate((int) $tahun, (int) $minggu)->startOfWeek();
        $akhirMinggu = Carbon::now()->setISODate((int) $tahun, (int) $minggu)->endOfWeek();

        $query->whereBetween('tanggal_mulai', [
            $awalMinggu->toDateString(),
            $akhirMinggu->toDateString(),
        ]);
    }

    if ($request->filled('bulan') && ! $request->filled('tanggal')) {
        $query->whereYear('tanggal_mulai', substr($request->bulan, 0, 4))
              ->whereMonth('tanggal_mulai', substr($request->bulan, 5, 2));
    }

    $filterLabel = 'Semua Periode';
    if ($request->filled('tanggal')) {
        $filterLabel = 'Tanggal ' . Carbon::parse($request->tanggal)->translatedFormat('d F Y');
    } elseif ($request->filled('bulan')) {
        $filterLabel = Carbon::parse($request->bulan . '-01')->translatedFormat('F Y');
    } elseif ($request->filled('minggu')) {
        $filterLabel = 'Minggu ' . $request->minggu;
    }

    $ruanganLabel = 'Semua Ruangan';
    if ($request->filled('ruangan_id')) {
        $ruangan = $ruangans->firstWhere('id', $request->ruangan_id);
        if ($ruangan) {
            $ruanganLabel = $ruangan->nama_ruangan;
        }
    }

    $data = $query->latest('tanggal_mulai')->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Tidak ada data untuk diekspor.');
    }

    try {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $html = view('admin.laporan-riwayat-pdf', compact('data', 'filterLabel', 'ruanganLabel'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-peminjaman-' . date('Y-m-d') . '.pdf"',
        ]);
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
    }
}
}