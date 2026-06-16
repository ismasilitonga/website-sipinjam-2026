<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamanBarang extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barang';

    protected $fillable = [
        'user_id', 'barang_id', 'nama_ormawa', 'jumlah',
        'tanggal_pinjam', 'tanggal_kembali_rencana', 'keperluan',
        'status', 'alasan_tolak',
        'waktu_diserahkan', 'diserahkan_oleh',
        'waktu_diterima_kembali', 'diterima_oleh',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_kembali_rencana' => 'date',
        'waktu_diserahkan'        => 'datetime',
        'waktu_diterima_kembali'  => 'datetime',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function barang() { return $this->belongsTo(Barang::class); }
    public function pengalihans() { return $this->hasMany(PengalihanBarang::class, 'peminjaman_barang_id'); }

    public function diserahkanOleh() { return $this->belongsTo(User::class, 'diserahkan_oleh'); }
    public function diterimaOleh()   { return $this->belongsTo(User::class, 'diterima_oleh'); }
}
