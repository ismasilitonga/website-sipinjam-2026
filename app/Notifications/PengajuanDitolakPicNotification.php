<?php

namespace App\Notifications;

use App\Models\PeminjamanRuangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Concerns\FormatsTanggalPeminjaman;

class PengajuanDitolakPicNotification extends Notification
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
            ->subject('Pengajuan Ruangan Ditolak - ' . $this->peminjaman_ruangans->ruangan->nama_ruangan)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Maaf, pengajuan ruangan kamu telah ditolak.')
            ->line('**Ruangan:** ' . $this->peminjaman_ruangans->ruangan->nama_ruangan)
            ->line('**Tanggal:** ' . $this->formatTanggalPeminjaman($this->peminjaman_ruangans))
            ->line('**Keperluan:** ' . $this->peminjaman_ruangans->keperluan)
            ->when($this->peminjaman_ruangans->alasan_tolak, fn($mail) =>
            $mail->line('**Alasan Penolakan:** ' . $this->peminjaman_ruangans->alasan_tolak))
            ->action('Lihat Riwayat', url('/'))
            ->line('Kamu dapat mengajukan kembali dengan menyesuaikan jadwal atau keperluan.')
            ->salutation('Salam, Tim SiPinjam');
    }
}