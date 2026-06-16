<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanRuangan;

class PengajuanRuanganNotification extends Notification
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
            ->subject('Pengajuan Ruangan Baru - ' . $this->peminjaman_ruangans->ruangan->nama_ruangan)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Ada pengajuan ruangan baru yang menunggu persetujuan kamu.')
            ->line('Pengaju: ' . $this->peminjaman_ruangans->user->name)
            ->line('Ormawa: ' . $this->peminjaman_ruangans->nama_ormawa)
            ->line('Ruangan: ' . $this->peminjaman_ruangans->ruangan->nama_ruangan)
            ->line('Tanggal: ' . $this->peminjaman_ruangans->tanggal_mulai)
            ->line('Keperluan: ' . $this->peminjaman_ruangans->keperluan)
            ->action('Lihat Pengajuan', url('/'))
            ->line('Segera proses pengajuan ini.')
            ->salutation('Salam, Tim SiPinjam');
    }
}