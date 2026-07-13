<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class AkunDisetujui extends Notification implements ShouldQueue
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
            ->subject('Pendaftaran Akun SiPinjam Anda Disetujui')
            ->greeting('Halo, ' . $this->user->nama . '!')
            ->line('Selamat! Pendaftaran akun Anda di SiPinjam sebagai ' . ucfirst($this->user->role) . ' untuk organisasi ' . $this->user->organisasi . ' telah disetujui oleh Admin.')
            ->line('Anda sekarang sudah bisa masuk menggunakan NIM dan kata sandi yang telah Anda daftarkan.')
            ->action('Masuk ke SiPinjam', route('landingpage.pilih-login'))
            ->line('Terima kasih telah menggunakan SiPinjam.');
    }
}