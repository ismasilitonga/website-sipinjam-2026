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
        'name'       => 'Administrator SC',
        'email'      => 'adminsipinjam@gmail.com',
        'nim'        => '123',
        'role'       => 'admin',
        'organisasi' => 'None',
        'status'     => 'aktif'
    ],
    [
        'name'       => 'Ahmad Fauzan',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '111',
        'role'       => 'pic',
        'organisasi' => 'DPM',
        'status'     => 'aktif',
        'lantai_pic' => 1,
    ],
    [
        'name'       => 'Ongku Permana Hasibuan',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '222',
        'role'       => 'pic',
        'organisasi' => 'DPM',
        'status'     => 'aktif',
        'lantai_pic' => 2,
    ],
    [
        'name'       => 'Lonyca Iska Majesty Simatupang',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '333',
        'role'       => 'pic',
        'organisasi' => 'DPM',
        'status'     => 'aktif',
        'lantai_pic' => 3,
    ],
    [
        'name'       => 'Budi Santoso',
        'email'      => 'sipinjamsc@gmail.com',
        'nim'        => '601',
        'role'       => 'pamdal',
        'organisasi' => 'None',
        'status'     => 'aktif',
    ],
    [
        'name'       => 'Rindiani Putri',
        'email'      => 'ketuasipinjam@gmail.com',
        'nim'        => '444',
        'role'       => 'ketua',
        'organisasi' => 'KUAS',
        'status'     => 'aktif',
    ],
    [
        'name'       => 'Fajar Ramadhan',
        'email'      => 'ketuasipinjam@gmail.com',
        'nim'        => '555',
        'role'       => 'ketua',
        'organisasi' => 'BEM',
        'status'     => 'aktif',
    ],
    [
        'name'       => 'Dimas Prasetyo',
        'email'      => 'ketuasipinjam@gmail.com',
        'nim'        => '999',
        'role'       => 'ketua',
        'organisasi' => 'BLUG',
        'status'     => 'aktif',
    ],
    [
        'name'       => 'Isma Silitonga',
        'email'      => 'ismariasilitonga@gmail.com',
        'nim'        => '666',
        'role'       => 'anggota',
        'organisasi' => 'KUAS',
        'status'     => 'aktif',
    ],
    [
        'name'       => 'Rizky Maulana',
        'email'      => 'ismariasilitonga@gmail.com',
        'nim'        => '777',
        'role'       => 'anggota',
        'organisasi' => 'BEM',
        'status'     => 'aktif',
    ],
    [
        'name'       => 'Wahyu Hidayat',
        'email'      => 'ismariasilitonga@gmail.com',
        'nim'        => '888',
        'role'       => 'anggota',
        'organisasi' => 'BLUG',
        'status'     => 'aktif',
    ],
];
    }
}