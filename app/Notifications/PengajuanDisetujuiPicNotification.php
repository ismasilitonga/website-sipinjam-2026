<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanRuangan;
use App\Notifications\Concerns\FormatsTanggalPeminjaman;

class PengajuanDisetujuiPicNotification extends Notification
{
    use Queueable, FormatsTanggalPeminjaman;

    public function __construct(public PeminjamanRuangan $peminjaman_ruangans) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Ruangan Disetujui - ' . $this->peminjaman_ruangans->ruangan->nama_ruangan)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Selamat! Pengajuan ruangan kamu telah disetujui.')
            ->line('**Ruangan:** ' . $this->peminjaman_ruangans->ruangan->nama_ruangan)
            ->line('**Tanggal:** ' . $this->formatTanggalPeminjaman($this->peminjaman_ruangans))
            ->line('**Keperluan:** ' . $this->peminjaman_ruangans->keperluan)
            ->action('Lihat Riwayat', url('/'))
            ->line('Silakan gunakan ruangan sesuai jadwal yang telah disetujui.')
            ->salutation('Salam, Tim SiPinjam');
    }
}