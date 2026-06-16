<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Insiden;

class TindakLanjutInsidenController extends Controller
{
    public function index()
    {
        $insidens = Insiden::with('user')->latest()->paginate(15);
        return view('PIC.laporan-insiden', compact('insidens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tindak_lanjut' => 'required|string',
            'status'        => 'required|in:ditindaklanjuti,selesai',
        ]);

        Insiden::findOrFail($id)->update([
            'tindak_lanjut' => $request->tindak_lanjut,
            'status'        => $request->status,
            'ditindak_oleh' => Auth::id(),
            'waktu_ditindak'   => now(),
        ]);

        return back()->with('success', 'Tindak lanjut berhasil disimpan.');
    }

    public function exportExcel()
    {
        $filename = 'laporan-insiden-' . date('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Pelapor', 'Judul', 'Lokasi', 'Status', 'Tindak Lanjut', 'Tanggal Lapor']);

            $data = Insiden::with('user')->latest()->get();
            foreach ($data as $i => $item) {
                fputcsv($file, [
                    $i + 1,
                    $item->user->name ?? '-',
                    $item->judul,
                    $item->lokasi,
                    ucfirst($item->status),
                    $item->tindak_lanjut ?? '-',
                    \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $insidens = Insiden::with('user')->latest()->get();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $html   = view('PIC.laporan-insiden-pdf', compact('insidens'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="laporan-insiden-' . date('Y-m-d') . '.pdf"',
        ]);
    }
}