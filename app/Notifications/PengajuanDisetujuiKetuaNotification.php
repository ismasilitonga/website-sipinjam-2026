<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanRuangan;

class PengajuanDisetujuiKetuaNotification extends Notification
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
            ->subject('Pengajuan Ruangan Disetujui Ketua - ' . $this->peminjaman->ruangan->nama)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Ada pengajuan ruangan yang sudah disetujui ketua dan menunggu validasi PIC.')
            ->line('**Pengaju:** ' . $this->peminjaman->user->name)
            ->line('**Ormawa:** ' . $this->peminjaman->nama_ormawa)
            ->line('**Ruangan:** ' . $this->peminjaman->ruangan->nama)
            ->line('**Tanggal:** ' . $this->peminjaman->tanggal_mulai)
            ->line('**Keperluan:** ' . $this->peminjaman->keperluan)
            ->action('Lihat Pengajuan', url('/'))
            ->line('Segera validasi pengajuan ini.')
            ->salutation('Salam, Tim SiPinjam');
    }
}