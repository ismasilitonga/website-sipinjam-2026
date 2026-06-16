<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory;
    protected $table = 'ruangan';
    
    protected $fillable = [
        'nama', 'kode', 'gedung', 'lantai', 'kapasitas', 'fasilitas', 'foto', 'status',
    ];

    public function peminjamanRuangans()
    {
        return $this->hasMany(PeminjamanRuangan::class);
    }
}
