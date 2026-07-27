<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanBarang;
use App\Notifications\Concerns\FormatsTanggalPeminjamanBarang;

class PengajuanBarangNotification extends Notification
{
    use Queueable, FormatsTanggalPeminjamanBarang;

    public function __construct(public PeminjamanBarang $peminjaman_barang) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
{
    $peminjaman  = $this->peminjaman_barang;
    $namaBarang  = $peminjaman->barang->nama ?? 'Tidak diketahui';
    $namaRuangan = $peminjaman->barang->ruangan->nama_ruangan ?? null;
    $lantai      = $peminjaman->barang->ruangan->lantai ?? null;

    return (new MailMessage)
        ->subject('Pengajuan Barang Baru - ' . $namaBarang)
        ->greeting('Halo, ' . $notifiable->nama)
        ->line('Ada pengajuan peminjaman barang baru yang menunggu persetujuan kamu.')
        ->line('Pengaju: ' . $peminjaman->user->nama)
        ->line('Ormawa: ' . ($peminjaman->nama_ormawa ?? '-'))
        ->line('Barang: ' . $namaBarang . ' (' . $peminjaman->jumlah . ' ' . ($peminjaman->barang->satuan ?? '') . ')')
        ->when($namaRuangan, fn($mail) =>
            $mail->line('Ruangan: ' . $namaRuangan . ' (Lt. ' . $lantai . ')'))
        ->line('Tanggal: ' . $this->formatTanggalPeminjamanBarang($peminjaman))
        ->line('Keperluan: ' . $peminjaman->keperluan)
        ->action('Lihat Pengajuan', url('/pic/validasi-peminjaman-barang'))
        ->line('Segera proses pengajuan ini.')
        ->salutation('Salam, Tim SiPinjam');
}
}