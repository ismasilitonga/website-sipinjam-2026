<?php

namespace App\Notifications;

use App\Models\PeminjamanRuangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanDitolakKetuaNotification extends Notification
{
    use Queueable;

    public function __construct(public PeminjamanRuangan $peminjaman) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Ruangan Ditolak Ketua - ' . $this->peminjaman->ruangan->nama)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Maaf, pengajuan ruangan kamu telah ditolak oleh ketua.')
            ->line('**Ruangan:** ' . $this->peminjaman->ruangan->nama)
            ->line('**Tanggal:** ' . \Carbon\Carbon::parse($this->peminjaman->tanggal_mulai)->translatedFormat('d F Y, H:i'))
            ->line('**Keperluan:** ' . $this->peminjaman->keperluan)
            ->when($this->peminjaman->alasan_tolak, fn($mail) =>
            $mail->line('**Alasan Penolakan:** ' . $this->peminjaman->alasan_tolak))
            ->action('Lihat Riwayat', url('/'))
            ->line('Kamu dapat mengajukan kembali dengan menyesuaikan jadwal atau keperluan.')
            ->salutation('Salam, Tim SiPinjam');
    }
}