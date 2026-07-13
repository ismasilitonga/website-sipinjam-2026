<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PeminjamanRuangan;
use App\Models\PeminjamanBarang;
use App\Notifications\AkunDisetujui;
use App\Notifications\AkunDitolak;
use Illuminate\Support\Facades\Notification;

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
        $user = User::findOrFail($id);

        if ($user->role === 'ketua') {
            $ketuaLama = User::where('organisasi', $user->organisasi)
                ->where('role', 'ketua')
                ->where('status', 'aktif')
                ->where('id', '!=', $user->id)
                ->first();

            if ($ketuaLama) {
                return back()->with('ketua_conflict', [
                    'pendaftar_id'    => $user->id,
                    'pendaftar_nama'  => $user->nama,
                    'ketua_lama_id'   => $ketuaLama->id,
                    'ketua_lama_nama' => $ketuaLama->nama,
                    'organisasi'      => $user->organisasi,
                ]);
            }
        }

        $user->update(['status' => 'aktif']);
        $this->tolakPendaftarKetuaLainYangBentrok($user);

        $user->notify(new AkunDisetujui($user));

        return back()->with('success', 'Akun berhasil diaktifkan.');
    }

    public function gantiKepengurusan($id)
    {
        $userBaru = User::findOrFail($id);

        $ketuaLama = User::where('organisasi', $userBaru->organisasi)
            ->where('role', 'ketua')
            ->where('status', 'aktif')
            ->where('id', '!=', $userBaru->id)
            ->first();

        if ($ketuaLama) {
            $ketuaLama->update(['status' => 'nonaktif']);
        }

        $userBaru->update(['status' => 'aktif']);
        $this->tolakPendaftarKetuaLainYangBentrok($userBaru);

        $userBaru->notify(new AkunDisetujui($userBaru));

        return back()->with('success',
            'Kepengurusan berhasil diganti. ' .
            ($ketuaLama ? $ketuaLama->nama . ' dinonaktifkan, ' : '') .
            $userBaru->nama . ' kini menjadi Ketua aktif.'
        );
    }

    private function tolakPendaftarKetuaLainYangBentrok(User $user): void
    {
        if ($user->role !== 'ketua') {
            return;
        }
        if (is_null($user->periode_mulai) || is_null($user->periode_selesai)) {
            return;
        }

        User::where('organisasi', $user->organisasi)
            ->where('role', 'ketua')
            ->where('status', 'pending')
            ->where('id', '!=', $user->id)
            ->where(function ($q) use ($user) {
                $q->whereNull('periode_mulai')
                  ->orWhereNull('periode_selesai')
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('periode_mulai', '<=', $user->periode_selesai)
                         ->where('periode_selesai', '>=', $user->periode_mulai);
                  });
            })
            ->update(['status' => 'ditolak']);
    }

    public function tolak($id)
    {
        $user = User::findOrFail($id);

        // Kirim notifikasi dulu sebelum data user dihapus
        Notification::route('mail', $user->email)
            ->notify(new AkunDitolak($user->nama, $user->organisasi));

        if ($user->bukti_ktm) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->bukti_ktm);
        }
        if ($user->bukti_sk) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->bukti_sk);
        }
        $user->delete();

        return back()->with('success', 'Pendaftar ditolak dan datanya telah dihapus.');
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