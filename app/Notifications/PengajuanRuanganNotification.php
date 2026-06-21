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
        $peminjaman = $this->peminjaman_ruangans;
        $namaRuangan = $peminjaman->ruangan->nama_ruangan ?? 'Tidak diketahui';

        return (new MailMessage)
            ->subject('Pengajuan Ruangan Baru - ' . $namaRuangan)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Ada pengajuan ruangan baru yang menunggu persetujuan kamu.')
            ->line('Pengaju: ' . $peminjaman->user->nama)
            ->line('Ormawa: ' . $peminjaman->nama_ormawa)
            ->line('Ruangan: ' . $namaRuangan)
            ->line('Tanggal: ' . $peminjaman->tanggal_mulai)
            ->line('Keperluan: ' . $peminjaman->keperluan)
            ->action('Lihat Pengajuan', url('/ketua/pengajuan-ruangan'))
            ->line('Segera proses pengajuan ini.')
            ->salutation('Salam, Tim SiPinjam');
    }
}