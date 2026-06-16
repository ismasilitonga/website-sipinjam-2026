<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengalihanBarang extends Model
{
    use HasFactory;

    protected $table = 'pengalihan_barang';

    protected $fillable = [
        'peminjaman_barang_id', 'dari_user_id', 'ke_user_id',
        'alasan', 'status', 'waktu_dikonfirmasi',
    ];

    protected $casts = [
        'waktu_dikonfirmasi' => 'datetime',
    ];

    public function peminjamanBarang() { return $this->belongsTo(PeminjamanBarang::class, 'peminjaman_barang_id'); }
    public function dariUser()         { return $this->belongsTo(User::class, 'dari_user_id'); }
    public function keUser()           { return $this->belongsTo(User::class, 'ke_user_id'); }
}
