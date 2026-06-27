<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeOtp extends Model
{
    protected $table = 'kode_otp'; 

    protected $fillable = [
        'email',
        'otp',
        'is_verified',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];
}