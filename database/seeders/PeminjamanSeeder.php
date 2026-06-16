<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PeminjamanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $anggota  = fn(string $email) => User::where('email', $email)->value('id');
        $ruangan  = fn(string $kode)  => DB::table('ruangans')->where('kode', $kode)->value('id');

        $peminjaman_ruangans = [
            // ── Sudah selesai (completed) ──────────────────────────
            [
                'user_id'                  => $anggota('isma@gmail.com'),
                'ruangan_id'               => $ruangan('SC-R01'),
                'nama_ormawa'              => 'KUAS',
                'tanggal_mulai'            => now()->subDays(10)->setTime(13, 0),
                'tanggal_selesai'          => now()->subDays(10)->setTime(15, 0),
                'keperluan'               => 'Rapat koordinasi mingguan divisi visual',
                'status'                   => 'disetujui',
                'status_pemakaian'         => 'completed',
                'waktu_checkin'               => now()->subDays(10)->setTime(13, 5),
                'waktu_checkout'              => now()->subDays(10)->setTime(14, 55),
                'waktu_kunci_diambil'         => now()->subDays(10)->setTime(12, 55),
                'waktu_kunci_dikembalikan'    => now()->subDays(10)->setTime(15, 5),
            ],
            [
                'user_id'                  => $anggota('rizky@gmail.com'),
                'ruangan_id'               => $ruangan('SC-R02'),
                'nama_ormawa'              => 'BEM',
                'tanggal_mulai'            => now()->subDays(5)->setTime(8, 0),
                'tanggal_selesai'          => now()->subDays(5)->setTime(12, 0),
                'keperluan'               => 'Musyawarah besar pengurus BEM semester ganjil',
                'status'                   => 'disetujui',
                'status_pemakaian'         => 'completed',
                'waktu_checkin'               => now()->subDays(5)->setTime(8, 10),
                'waktu_checkout'              => now()->subDays(5)->setTime(11, 50),
                'waktu_kunci_diambil'         => now()->subDays(5)->setTime(7, 55),
                'waktu_kunci_dikembalikan'    => now()->subDays(5)->setTime(12, 5),
            ],

            // ── Sedang berlangsung (ongoing) ───────────────────────
            [
                'user_id'                  => $anggota('nadia@gmail.com'),
                'ruangan_id'               => $ruangan('SC-R03'),
                'nama_ormawa'              => 'HMTI',
                'tanggal_mulai'            => now()->setTime(9, 0),
                'tanggal_selesai'          => now()->setTime(12, 0),
                'keperluan'               => 'Workshop Arduino dan IoT untuk anggota baru',
                'status'                   => 'disetujui',
                'status_pemakaian'         => 'ongoing',
                'waktu_checkin'               => now()->setTime(9, 5),
                'waktu_checkout'              => null,
                'waktu_kunci_diambil'         => now()->setTime(8, 55),
                'waktu_kunci_dikembalikan'    => null,
            ],

            // ── Disetujui, belum mulai (booked) ───────────────────
            [
                'user_id'                  => $anggota('wahyu@gmail.com'),
                'ruangan_id'               => $ruangan('SC-L02'),
                'nama_ormawa'              => 'BLUG',
                'tanggal_mulai'            => now()->addDays(2)->setTime(14, 0),
                'tanggal_selesai'          => now()->addDays(2)->setTime(17, 0),
                'keperluan'               => 'Sesi instalasi Linux untuk mahasiswa baru BLUG',
                'status'                   => 'disetujui',
                'status_pemakaian'         => 'booked',
                'waktu_checkin'               => null,
                'waktu_checkout'              => null,
                'waktu_kunci_diambil'         => null,
                'waktu_kunci_dikembalikan'    => null,
            ],
            [
                'user_id'                  => $anggota('putri@gmail.com'),
                'ruangan_id'               => $ruangan('SC-L01'),
                'nama_ormawa'              => 'MAPALA',
                'tanggal_mulai'            => now()->addDays(3)->setTime(16, 0),
                'tanggal_selesai'          => now()->addDays(3)->setTime(18, 0),
                'keperluan'               => 'Briefing persiapan pendakian Gunung Daik',
                'status'                   => 'disetujui',
                'status_pemakaian'         => 'booked',
                'waktu_checkin'               => null,
                'waktu_checkout'              => null,
                'waktu_kunci_diambil'         => null,
                'waktu_kunci_dikembalikan'    => null,
            ],

            // ── Menunggu persetujuan ketua ─────────────────────────
            [
                'user_id'                  => $anggota('isma@gmail.com'),
                'ruangan_id'               => $ruangan('SC-R01'),
                'nama_ormawa'              => 'KUAS',
                'tanggal_mulai'            => now()->addDays(7)->setTime(13, 0),
                'tanggal_selesai'          => now()->addDays(7)->setTime(16, 0),
                'keperluan'               => 'Pameran karya seni semester akhir KUAS',
                'status'                   => 'menunggu_ketua',
                'status_pemakaian'         => 'booked',
                'waktu_checkin'               => null,
                'waktu_checkout'              => null,
                'waktu_kunci_diambil'         => null,
                'waktu_kunci_dikembalikan'    => null,
            ],

            // ── Menunggu persetujuan PIC ───────────────────────────
            [
                'user_id'                  => $anggota('rizky@gmail.com'),
                'ruangan_id'               => $ruangan('SC-R02'),
                'nama_ormawa'              => 'BEM',
                'tanggal_mulai'            => now()->addDays(4)->setTime(8, 0),
                'tanggal_selesai'          => now()->addDays(4)->setTime(17, 0),
                'keperluan'               => 'Orientasi mahasiswa baru Polibatam 2026',
                'status'                   => 'menunggu_pic',
                'status_pemakaian'         => 'booked',
                'waktu_checkin'               => null,
                'waktu_checkout'              => null,
                'waktu_kunci_diambil'         => null,
                'waktu_kunci_dikembalikan'    => null,
            ],

            // ── Ditolak ────────────────────────────────────────────
            [
                'user_id'                  => $anggota('nadia@gmail.com'),
                'ruangan_id'               => $ruangan('SC-M01'),
                'nama_ormawa'              => 'HMTI',
                'tanggal_mulai'            => now()->subDays(3)->setTime(10, 0),
                'tanggal_selesai'          => now()->subDays(3)->setTime(12, 0),
                'keperluan'               => 'Pembuatan konten video profil HMTI',
                'status'                   => 'ditolak',
                'alasan_tolak'             => 'Ruangan Podcast & Multimedia sedang dalam perbaikan perangkat.',
                'status_pemakaian'         => 'booked',
                'waktu_checkin'               => null,
                'waktu_checkout'              => null,
                'waktu_kunci_diambil'         => null,
                'waktu_kunci_dikembalikan'    => null,
            ],
        ];

        foreach ($peminjaman_ruangans as $data) {
            DB::table('peminjaman_ruangans')->insert([
                ...$data,
                'alasan_tolak'  => $data['alasan_tolak'] ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $this->command->info('✅ PeminjamanSeeder: ' . count($peminjaman_ruangans) . ' peminjaman ruangan dibuat.');
    }
}
