<?php

namespace App\Notifications;

use App\Models\PeminjamanRuangan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Concerns\FormatsTanggalPeminjaman;

class PengajuanDisetujuiKetuaNotification extends Notification
{
    use Queueable, FormatsTanggalPeminjaman;

    public function __construct(public PeminjamanRuangan $peminjaman) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Ruangan Disetujui Ketua - ' . $this->peminjaman->ruangan->nama_ruangan)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Ada pengajuan ruangan yang sudah disetujui ketua dan menunggu validasi PIC.')
            ->line('**Pengaju:** ' . $this->peminjaman->user->nama)
            ->line('**Ormawa:** ' . $this->peminjaman->nama_ormawa)
            ->line('**Ruangan:** ' . $this->peminjaman->ruangan->nama_ruangan)
            ->line('**Tanggal:** ' . $this->formatTanggalPeminjaman($this->peminjaman))
            ->line('**Keperluan:** ' . $this->peminjaman->keperluan)
            ->action('Lihat Pengajuan', url('/'))
            ->line('Segera validasi pengajuan ini.')
            ->salutation('Salam, Tim SiPinjam');
    }
}