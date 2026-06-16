<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamanRuangan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_ruangan';

    protected $fillable = [
    'user_id',
    'ruangan_id',
    'nama_ormawa',
    'tanggal_mulai',
    'tanggal_selesai',
    'keperluan',
    'status',
    'alasan_tolak',
    'waktu_kunci_diambil',
    'waktu_kunci_dikembalikan',
];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'waktu_kunci_diambil' => 'datetime',
        'waktu_kunci_dikembalikan' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Relasi ke check_in (1-to-1)
    public function checkIn()
    {
        return $this->hasOne(CheckIn::class, 'peminjaman_id');
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }
}