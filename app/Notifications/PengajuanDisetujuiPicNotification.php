<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanRuangan;

class PengajuanDisetujuiPicNotification extends Notification
{
    use Queueable;

    public function __construct(public PeminjamanRuangan $peminjaman_ruangans) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Ruangan Disetujui - ' . $this->peminjaman_ruangans->ruangan->nama)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Selamat! Pengajuan ruangan kamu telah disetujui.')
            ->line('**Ruangan:** ' . $this->peminjaman_ruangans->ruangan->nama)
            ->line('**Tanggal:** ' . $this->peminjaman_ruangans->tanggal_mulai)
            ->line('**Keperluan:** ' . $this->peminjaman_ruangans->keperluan)
            ->action('Lihat Riwayat', url('/'))
            ->line('Silakan gunakan ruangan sesuai jadwal yang telah disetujui.')
            ->salutation('Salam, Tim SiPinjam');
    }
}