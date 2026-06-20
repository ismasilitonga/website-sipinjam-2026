<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuanganSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $ruangan = [
            [
                'nama_ruangan'      => 'Ruang Rapat Utama',
                'kode'      => 'SC-R01',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '1',
                'kapasitas' => 30,
                'fasilitas' => 'AC, Proyektor, Whiteboard, Meja Rapat, Kursi',
                'status'    => 'tersedia',
            ],
            [
                'nama_ruangan'      => 'Ruang Serbaguna A',
                'kode'      => 'SC-R02',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '1',
                'kapasitas' => 60,
                'fasilitas' => 'AC, Proyektor, Sound System, Podium, Kursi Lipat',
                'status'    => 'tersedia',
            ],
            [
                'nama_ruangan'      => 'Ruang Serbaguna B',
                'kode'              => 'SC-R03',
                'gedung'            => 'Gedung Student Center',
                'lantai'            => '2',
                'kapasitas'         => 50,
                'fasilitas'         => 'AC, Proyektor, Whiteboard, Kursi Lipat',
                'status'            => 'tersedia',
            ],
            [
                'nama'      => 'Ruang Sekretariat BEM',
                'kode'      => 'SC-S01',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '1',
                'kapasitas' => 15,
                'fasilitas' => 'AC, Meja Kerja, Lemari Arsip',
                'status'    => 'tersedia',
            ],
            [
                'nama'      => 'Ruang Sekretariat DPM',
                'kode'      => 'SC-S02',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '1',
                'kapasitas' => 15,
                'fasilitas' => 'AC, Meja Kerja, Lemari Arsip',
                'status'    => 'tersedia',
            ],
            [
                'nama'      => 'Ruang Latihan MAPALA',
                'kode'      => 'SC-L01',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '2',
                'kapasitas' => 20,
                'fasilitas' => 'Kipas Angin, Storage Peralatan',
                'status'    => 'tersedia',
            ],
            [
                'nama'      => 'Studio KUAS',
                'kode'      => 'SC-L02',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '2',
                'kapasitas' => 20,
                'fasilitas' => 'AC, Meja Studio, Pencahayaan Seni',
                'status'    => 'tersedia',
            ],
            [
                'nama'      => 'Ruang Podcast & Multimedia',
                'kode'      => 'SC-M01',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '2',
                'kapasitas' => 10,
                'fasilitas' => 'AC, Perangkat Podcast, Kamera, Lighting, Green Screen',
                'status'    => 'tidak_tersedia',
            ],
            [
                'nama'      => 'Ruang Arsip & Dokumentasi',
                'kode'      => 'SC-A01',
                'gedung'    => 'Gedung Student Center',
                'lantai'    => '1',
                'kapasitas' => 5,
                'fasilitas' => 'AC, Lemari Arsip, Meja Kerja',
                'status'    => 'tersedia',
            ],
            [
                'nama'      => 'Ruang Kegiatan Luar Ruangan',
                'kode'      => 'SC-O01',
                'gedung'    => 'Area Terbuka Student Center',
                'lantai'    => '0',
                'kapasitas' => 100,
                'fasilitas' => 'Tenda, Panggung Portable, Sound System Outdoor',
                'status'    => 'tersedia',
            ],
        ];

        foreach ($ruangan as $r) {
    DB::table('ruangan')->updateOrInsert(
        ['kode' => $r['kode']],
        [
            ...$r,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
}

$this->command->info('✅ RuanganSeeder: ' . count($ruangan) . ' ruangan dibuat.');

    }
}