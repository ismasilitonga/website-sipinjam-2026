<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanBarang;
use App\Notifications\Concerns\FormatsTanggalPeminjamanBarang;

class PengajuanBarangDisetujuiNotification extends Notification
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
            ->subject('Pengajuan Barang Disetujui - ' . $namaBarang)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Selamat! Pengajuan peminjaman barang kamu telah disetujui.')
            ->line('**Barang:** ' . $namaBarang . ' (' . $peminjaman->jumlah . ' ' . ($peminjaman->barang->satuan ?? '') . ')')
            ->line('**Tanggal:** ' . $this->formatTanggalPeminjamanBarang($peminjaman))
            ->line('**Keperluan:** ' . $peminjaman->keperluan)
            ->action('Lihat Riwayat', url('/'))
            ->line('Silakan ambil barang sesuai jadwal yang telah disetujui.')
            ->salutation('Salam, Tim SiPinjam');
    }
}