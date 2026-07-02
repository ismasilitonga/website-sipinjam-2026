<?php

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
    [
        'nama'       => 'Administrator SC',
        'email'      => 'adminsipinjam@gmail.com',
        'nim'        => '123',
        'role'       => 'admin',
        'organisasi' => 'None',
        'status'     => 'aktif'
    ],
    [
        'nama'       => 'Ahmad Fauzan',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '111',
        'role'       => 'pic',
        'organisasi' => 'DPM',
        'status'     => 'aktif',
        'lantai_pic' => 1,
    ],
    [
        'nam'       => 'Ongku Permana Hasibuan',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '222',
        'role'       => 'pic',
        'organisasi' => 'DPM',
        'status'     => 'aktif',
        'lantai_pic' => 2,
    ],
    [
        'nama'       => 'Lonyca Iska Majesty Simatupang',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '333',
        'role'       => 'pic',
        'organisasi' => 'DPM',
        'status'     => 'aktif',
        'lantai_pic' => 3,
    ],
    [
        'nama'       => 'Budi Santoso',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '601',
        'role'       => 'pamdal',
        'organisasi' => 'None',
        'status'     => 'aktif',
    ],
    [
        'nama'       => 'Muhammad Riski Supriyono',
        'email'      => 'ketuasipinjam@gmail.com',
        'nim'        => '444',
        'role'       => 'ketua',
        'organisasi' => 'KUAS',
        'status'     => 'aktif',
    ],
    [
        'nama'       => 'Fajar Ramadhan',
        'email'      => 'ketuasipinjam@gmail.com',
        'nim'        => '555',
        'role'       => 'ketua',
        'organisasi' => 'BEM',
        'status'     => 'aktif',
    ],
    [
        'nama'       => 'Dimas Prasetyo',
        'email'      => 'ketuasipinjam@gmail.com',
        'nim'        => '999',
        'role'       => 'ketua',
        'organisasi' => 'BLUG',
        'status'     => 'aktif',
    ],
    [
        'nama'       => 'Isma Silitonga',
        'email'      => 'ismariasilitonga@gmail.com',
        'nim'        => '666',
        'role'       => 'anggota',
        'organisasi' => 'KUAS',
        'status'     => 'aktif',
    ],
    [
        'nama'       => 'Rizky Maulana',
        'email'      => 'ismariasilitonga@gmail.com',
        'nim'        => '777',
        'role'       => 'anggota',
        'organisasi' => 'BEM',
        'status'     => 'aktif',
    ],
    [
        'nama'       => 'Wahyu Hidayat',
        'email'      => 'ismariasilitonga@gmail.com',
        'nim'        => '888',
        'role'       => 'anggota',
        'organisasi' => 'BLUG',
        'status'     => 'aktif',
    ],
];
    }
}