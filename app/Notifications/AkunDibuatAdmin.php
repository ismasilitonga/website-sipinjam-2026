<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class AkunDibuatAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public \App\Models\User $user) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun SiPinjam Anda Telah Dibuat')
            ->greeting('Halo, ' . $this->user->nama . '!')
            ->line('Admin telah membuatkan akun SiPinjam untuk Anda sebagai ' . ucfirst($this->user->role) .
                ($this->user->organisasi ? ' di organisasi ' . $this->user->organisasi : '') . '.')
            ->line('Akun Anda sudah aktif dan siap digunakan. Silakan masuk menggunakan NIM Anda dan kata sandi yang telah diberikan oleh Admin.')
            ->line('Demi keamanan, kami sarankan Anda segera mengganti kata sandi setelah berhasil masuk.')
            ->action('Masuk ke SiPinjam', route('landingpage.pilih-login'))
            ->line('Terima kasih telah menggunakan SiPinjam.');
    }
}