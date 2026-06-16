<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanRuangan;

class PengajuanDitolakPicNotification extends Notification
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
            ->subject('Pengajuan Ruangan Ditolak - ' . $this->peminjaman_ruangans->ruangan->nama)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Maaf, pengajuan ruangan kamu ditolak oleh PIC.')
            ->line('**Ruangan:** ' . $this->peminjaman_ruangans->ruangan->nama)
            ->line('**Tanggal:** ' . $this->peminjaman_ruangans->tanggal_mulai)
            ->line('**Alasan Tolak:** ' . $this->peminjaman_ruangans->alasan_tolak)
            ->action('Lihat Riwayat', url('/'))
            ->line('Silakan ajukan kembali jika diperlukan.')
            ->salutation('Salam, Tim SiPinjam');
    }
}