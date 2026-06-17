<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $fillable = [
    'nama',
    'kode',
    'kategori',
    'stok',
    'satuan',
    'kondisi',
    'foto',
    'deskripsi',
    'organisasi',
    'jenis_barang',
];

    public function peminjamanBarangs()
    {
        return $this->hasMany(PeminjamanBarang::class);
    }
}
