<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AkunDitolak extends Notification
{
    public function __construct(public string $nama, public string $organisasi) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pendaftaran Akun SiPinjam Ditolak')
            ->greeting('Halo, ' . $this->nama . '.')
            ->line('Mohon maaf, pendaftaran akun Anda untuk organisasi ' . $this->organisasi . ' di SiPinjam tidak dapat disetujui oleh Admin.')
            ->line('Jika Anda merasa ini keliru, silakan hubungi Admin Student Center untuk klarifikasi lebih lanjut.');
    }
}