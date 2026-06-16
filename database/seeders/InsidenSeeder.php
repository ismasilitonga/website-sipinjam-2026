<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InsidenSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = fn(string $email) => User::where('email', $email)->value('id');
        $pic  = fn(string $email) => User::where('email', $email)->value('id');

        $insidens = [
            [
                'user_id'         => $user('isma@gmail.com'),
                'judul'           => 'Kursi Patah di Ruang Rapat Utama',
                'deskripsi'       => 'Ditemukan 2 kursi lipat dengan kaki patah setelah kegiatan rapat KUAS. Kursi tersebut tidak aman untuk digunakan.',
                'lokasi'          => 'Ruang Rapat Utama (SC-R01)',
                'status'          => 'selesai',
                'tindak_lanjut'   => 'Kursi telah dipindahkan ke gudang untuk diperbaiki. Penggantian 2 kursi baru sudah dilakukan.',
                'ditindak_oleh'   => $pic('pic1@gmail.com'),
                'waktu_ditindak'     => now()->subDays(8),
                'created_at'      => now()->subDays(9),
            ],
            [
                'user_id'         => $user('rizky@gmail.com'),
                'judul'           => 'AC Bocor di Ruang Serbaguna B',
                'deskripsi'       => 'AC unit sebelah kiri mengeluarkan tetesan air yang menggenang di lantai, berpotensi menyebabkan kecelakaan.',
                'lokasi'          => 'Ruang Serbaguna B (SC-R03)',
                'status'          => 'selesai',
                'tindak_lanjut'   => 'Tim teknisi kampus telah membersihkan saluran air AC. AC sudah berfungsi normal.',
                'ditindak_oleh'   => $pic('pic2@gmail.com'),
                'waktu_ditindak'     => now()->subDays(3),
                'created_at'      => now()->subDays(4),
            ],

            [
                'user_id'         => $user('nadia@gmail.com'),
                'judul'           => 'Proyektor Tidak Bisa Terhubung HDMI',
                'deskripsi'       => 'Proyektor BRG-EL-001 tidak dapat mendeteksi sinyal HDMI dari laptop apapun. Sudah dicoba dengan 3 laptop berbeda dan 2 kabel HDMI berbeda.',
                'lokasi'          => 'Ruang Serbaguna A (SC-R02)',
                'status'          => 'ditindaklanjuti',
                'tindak_lanjut'   => 'Port HDMI proyektor sedang diperiksa oleh teknisi. Sementara gunakan proyektor BRG-EL-002 sebagai pengganti.',
                'ditindak_oleh'   => $pic('pic1@gmail.com'),
                'waktu_ditindak'     => now()->subDays(1),
                'created_at'      => now()->subDays(2),
            ],

            [
                'user_id'         => $user('wahyu@gmail.com'),
                'judul'           => 'Lampu Mati di Koridor Lantai 2',
                'deskripsi'       => 'Dua lampu LED di koridor menuju Studio KUAS mati total, membuat area menjadi gelap terutama malam hari. Berpotensi membahayakan pengguna.',
                'lokasi'          => 'Koridor Lantai 2 Student Center',
                'status'          => 'dilaporkan',
                'tindak_lanjut'   => null,
                'ditindak_oleh'   => null,
                'waktu_ditindak'     => null,
                'created_at'      => now()->subHours(3),
            ],
            [
                'user_id'         => $user('putri@gmail.com'),
                'judul'           => 'Kebersihan Toilet Kurang Terjaga',
                'deskripsi'       => 'Toilet di lantai 1 Student Center dalam kondisi kotor dan ada keran yang menetes terus. Sudah berlangsung sejak 2 hari lalu.',
                'lokasi'          => 'Toilet Lantai 1 Student Center',
                'status'          => 'dilaporkan',
                'tindak_lanjut'   => null,
                'ditindak_oleh'   => null,
                'waktu_ditindak'     => null,
                'created_at'      => now()->subHours(1),
            ],
        ];

        foreach ($insidens as $data) {
            DB::table('insidens')->insert([
                ...$data,
                'foto'       => null,
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ InsidenSeeder: ' . count($insidens) . ' insiden dibuat.');
    }
}
