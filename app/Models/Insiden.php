<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insiden extends Model
{
    use HasFactory;
    protected $table = 'insiden';
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'lokasi', 'foto',
        'status', 'tindak_lanjut', 'ditindak_oleh', 'waktu_ditindak',
    ];

    protected $casts = [
        'waktu_ditindak' => 'datetime',
    ];

    public function user()        { return $this->belongsTo(User::class); }
    public function ditindakOleh(){ return $this->belongsTo(User::class, 'ditindak_oleh'); }
}
