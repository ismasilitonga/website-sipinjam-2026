<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'nama',
    'email',
    'nim',
    'role',
    'organisasi',
    'ormawa_id',
    'lantai_pic',
    'periode_mulai',
    'periode_mulai_bulan',
    'periode_selesai',
    'periode_selesai_bulan',
    'password',
    'status',
    'bukti_ktm',
    'bukti_sk',
];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function batasAkhirJabatan(): ?\Carbon\Carbon
    {
        if (!$this->periode_selesai) {
            return null;
        }

        $bulan = $this->periode_selesai_bulan ?? 12;

        return \Carbon\Carbon::createFromDate($this->periode_selesai, $bulan, 1)->endOfMonth();
    }

    public function masaJabatanBerakhir(): bool
    {
        $batas = $this->batasAkhirJabatan();
        return $batas !== null && $batas->isPast();
    }

    public function peminjamans()
    {
        return $this->hasMany(PeminjamanRuangan::class);
    }

    public function peminjamanBarangs()
    {
        return $this->hasMany(PeminjamanBarang::class);
    }

    public function insidens()
    {
        return $this->hasMany(Insiden::class);
    }

    public function ormawa()
    {
        return $this->belongsTo(Ormawa::class);
    }
}