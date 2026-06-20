<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

       protected $fillable = [
        'nama', 'email','nim','role','organisasi','ormawa_id','password','status',
    ];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

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