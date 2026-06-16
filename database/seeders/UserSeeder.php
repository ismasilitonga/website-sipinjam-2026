<?php

/* ═══════════════════════════════════════════════════════════
 *  USER SEEDER
 * ═══════════════════════════════════════════════════════════ */
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            // ── Super Admin ──────────────────────────────────────
            [
                'name'       => 'Administrator SC',
                'email'      => 'adminsipinjam@gmail.com',
                'nim'        => '123',
                'role'       => 'admin',
                'organisasi' => 'None',
                'status' => 'aktif'
            ],

            // ── PIC (3 orang) ────────────────────────────────────
            [
                'name'       => 'Rey Sastria Harianja',
                'email'      => 'ismariasilitonga@gmail.com',
                'nim'        => '123',
                'role'       => 'pic',
                'organisasi' => 'DPM',
                'status'     => 'aktif',
                'lantai_pic' => 1,
            ],
            [
                'name'       => 'Akyasa Fikiri Ramadhan',
                'email'      => 'ismariasilitonga@gmail.com',
                'nim'        => '222',
                'role'       => 'pic',
                'organisasi' => 'DPM',
                'status'     => 'aktif',
                'lantai_pic' => 2,
            ],
            [
                'name'       => 'Ahmad Fauzan',
                'email'      => 'ismariasilitonga@gmail.com',
                'nim'        => '333',
                'role'       => 'pic',
                'organisasi' => 'DPM',
                'status'     => 'aktif',
                'lantai_pic' => 3,
            ],

            // ── Pamdal ───────────────────────────────────────────
            [
                'name'       => 'Budi Santoso',
                'email'      => 'sipinjamsc@gmail.com',
                'nim'        => '123',
                'role'       => 'pamdal',
                'organisasi' => 'None',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Agus Prayogi',
                'email'      => 'pamdal2@gmail.com',
                'nim'        => '123',
                'role'       => 'pamdal',
                'organisasi' => 'None',
                'status'  => 'aktif',
            ],

            // ── Ketua Ormawa ─────────────────────────────────────
            [
                'name'       => 'Rindiani Putri',
                'email'      => 'sipinjamsc@gmail.com',
                'nim'        => '123',
                'role'       => 'ketua',
                'organisasi' => 'KUAS',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Fajar Ramadhan',
                'email'      => 'ketua.bem@gmail.com',
                'nim'        => '444',
                'role'       => 'ketua',
                'organisasi' => 'BEM',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Tiara Anggraini',
                'email'      => 'ketua.hmti@gmail.com',
                'nim'        => '123',
                'role'       => 'ketua',
                'organisasi' => 'HMTI',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Dimas Prasetyo',
                'email'      => 'ketua.blug@gmail.com',
                'nim'        => '123',
                'role'       => 'ketua',
                'organisasi' => 'BLUG',
                'status'  => 'aktif',
            ],

            // ── Anggota Ormawa ───────────────────────────────────
            [
                'name'       => 'Isma Silitonga',
                'email'      => 'ismariasilitonga@gmail.com',
                'nim'        => '123',
                'role'       => 'anggota',
                'organisasi' => 'KUAS',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Rizky Maulana',
                'email'      => 'rizky@gmail.com',
                'nim'        => '555',
                'role'       => 'anggota',
                'organisasi' => 'BEM',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Nadia Salsabila',
                'email'      => 'nadia@gmail.com',
                'nim'        => '123',
                'role'       => 'anggota',
                'organisasi' => 'HMTI',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Wahyu Hidayat',
                'email'      => 'wahyu@gmail.com',
                'nim'        => '123',
                'role'       => 'anggota',
                'organisasi' => 'BLUG',
                'status'  => 'aktif',
            ],
            [
                'name'       => 'Putri Rahayu',
                'email'      => 'putri@gmail.com',
                'nim'        => '123',
                'role'       => 'anggota',
                'organisasi' => 'MAPALA',
                'status'  => 'aktif',
            ],
        ];

    foreach ($users as $data) {
    User::firstOrCreate(
        ['nim' => $data['nim'], 'role' => $data['role']],
        [
            ...$data,
            'password'          => Hash::make('123'),
            'email_verified_at' => now(),
        ]
    );
}
$this->command->info('✅ UserSeeder: ' . count($users) . ' user dibuat.');
    }
}