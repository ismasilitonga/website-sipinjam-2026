<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanBarang;
use App\Notifications\Concerns\FormatsTanggalPeminjamanBarang;

class PengajuanBarangDitolakNotification extends Notification
{
    use Queueable, FormatsTanggalPeminjamanBarang;

    public function __construct(public PeminjamanBarang $peminjaman_barang) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $peminjaman = $this->peminjaman_barang;
        $namaBarang = $peminjaman->barang->nama ?? 'Tidak diketahui';

        return (new MailMessage)
            ->subject('Pengajuan Barang Ditolak - ' . $namaBarang)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Maaf, pengajuan peminjaman barang kamu telah ditolak.')
            ->line('**Barang:** ' . $namaBarang . ' (' . $peminjaman->jumlah . ' ' . ($peminjaman->barang->satuan ?? '') . ')')
            ->line('**Tanggal:** ' . $this->formatTanggalPeminjamanBarang($peminjaman))
            ->line('**Keperluan:** ' . $peminjaman->keperluan)
            ->when($peminjaman->alasan_tolak, fn($mail) =>
                $mail->line('**Alasan Penolakan:** ' . $peminjaman->alasan_tolak))
            ->action('Lihat Riwayat', url('/'))
            ->line('Kamu dapat mengajukan kembali dengan menyesuaikan jadwal atau keperluan.')
            ->salutation('Salam, Tim SiPinjam');
    }
}