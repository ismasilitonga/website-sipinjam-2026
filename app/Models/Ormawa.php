<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ormawa extends Model
{
    protected $table = 'ormawa';
protected $fillable = [
    'singkatan',
    'nama_organisasi',
    'kategori',
    'punya_pic',
    'kontak',
    'deskripsi',
    'status',
];

    public function users()
    {
        return $this->hasMany(User::class, 'organisasi', 'singkatan');
    }
}