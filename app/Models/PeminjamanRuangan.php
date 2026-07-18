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
        'dokumen_pendukung',
        'status',
        'status_pemakaian',
        'alasan_tolak',
        'ditolak_oleh',
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

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'peminjaman_id');
    }

    public function checkInHariIni()
    {
        return $this->hasOne(CheckIn::class, 'peminjaman_id')->whereDate('tanggal', today());
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    /**
     * Sumber kebenaran TUNGGAL untuk status penggunaan ruangan HARI INI.
     * Dipakai oleh SEMUA dashboard (Anggota, PIC, Ketua, Admin) supaya
     * tidak ada lagi logic status yang beda-beda / tidak sinkron.
     *
     * Nilai balik:
     * - 'sedang_digunakan' : sudah check-in / kunci diambil hari ini, belum checkout/kembali
     * - 'selesai_hari_ini' : sudah checkout/kembali kunci hari ini
     * - 'selesai'          : peminjaman keseluruhan sudah berstatus selesai (di luar hari ini)
     * - 'akan_digunakan'   : belum ada aktivitas check-in hari ini
     */
    public function getStatusHariIniAttribute(): string
    {
        // Pakai relasi yang sudah di-eager-load jika ada, hindari query N+1
        $checkin = $this->relationLoaded('checkInHariIni')
            ? $this->checkInHariIni
            : $this->checkInHariIni()->first();

        if ($checkin && is_null($checkin->waktu_checkout)) {
            return 'sedang_digunakan';
        }

        if ($checkin && $checkin->waktu_checkout) {
            return 'selesai_hari_ini';
        }

        if ($this->status === 'selesai') {
            return 'selesai';
        }

        return 'akan_digunakan';
    }
}