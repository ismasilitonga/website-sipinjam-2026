<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckIn extends Model
{
    use HasFactory;

    protected $table = 'check_in';

    protected $fillable = [
        'peminjaman_id',
        'tanggal',
        'foto_ktp',
        'waktu_checkin',
        'waktu_checkout',
        'status_kunci',
    ];

    protected $casts = [
        'waktu_checkin' => 'datetime',
        'waktu_checkout' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanRuangan::class, 'peminjaman_id');
    }
}