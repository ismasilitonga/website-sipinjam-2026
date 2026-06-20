<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarangSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $barang = [

            ['nama' => 'Proyektor Portable',    'kode' => 'BRG-EL-001', 'kategori' => 'Elektronik',   'stok' => 4,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Proyektor HDMI portable untuk presentasi'],
            ['nama' => 'Layar Proyektor',        'kode' => 'BRG-EL-002', 'kategori' => 'Elektronik',   'stok' => 3,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Layar proyektor roll-up 100 inch'],
            ['nama' => 'Speaker Portable',       'kode' => 'BRG-EL-003', 'kategori' => 'Elektronik',   'stok' => 5,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Speaker bluetooth aktif untuk acara'],
            ['nama' => 'Microphone Wireless',    'kode' => 'BRG-EL-004', 'kategori' => 'Elektronik',   'stok' => 6,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Mic wireless UHF dengan clip-on'],
            ['nama' => 'Laptop Presentasi',      'kode' => 'BRG-EL-005', 'kategori' => 'Elektronik',   'stok' => 2,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Laptop untuk keperluan presentasi kegiatan'],
            ['nama' => 'Kabel HDMI',             'kode' => 'BRG-EL-006', 'kategori' => 'Elektronik',   'stok' => 10, 'satuan' => 'buah',  'kondisi' => 'baik',         'deskripsi' => 'Kabel HDMI 3 meter'],
            ['nama' => 'Mixer Audio',            'kode' => 'BRG-EL-007', 'kategori' => 'Elektronik',   'stok' => 1,  'satuan' => 'unit',  'kondisi' => 'rusak_ringan', 'deskripsi' => 'Mixer audio 8 channel, tombol channel 3 agak longgar'],

            // Mebel & Perlengkapan
            ['nama' => 'Meja Lipat',             'kode' => 'BRG-MB-001', 'kategori' => 'Mebel',        'stok' => 20, 'satuan' => 'buah',  'kondisi' => 'baik',         'deskripsi' => 'Meja lipat plastik ukuran 60x120 cm'],
            ['nama' => 'Kursi Lipat',            'kode' => 'BRG-MB-002', 'kategori' => 'Mebel',        'stok' => 80, 'satuan' => 'buah',  'kondisi' => 'baik',         'deskripsi' => 'Kursi lipat besi standar'],
            ['nama' => 'Podium',                 'kode' => 'BRG-MB-003', 'kategori' => 'Mebel',        'stok' => 2,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Podium kayu untuk acara formal'],
            ['nama' => 'Backdrop Banner Stand',  'kode' => 'BRG-MB-004', 'kategori' => 'Mebel',        'stok' => 3,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Stand backdrop x-banner adjustable'],

            // Dekorasi & Acara
            ['nama' => 'Tenda Gazebo 3x3',       'kode' => 'BRG-DK-001', 'kategori' => 'Dekorasi',     'stok' => 4,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Tenda lipat untuk kegiatan outdoor'],
            ['nama' => 'Spanduk Polos',          'kode' => 'BRG-DK-002', 'kategori' => 'Dekorasi',     'stok' => 5,  'satuan' => 'lembar','kondisi' => 'baik',         'deskripsi' => 'Spanduk kain polos putih 1x3 meter'],
            ['nama' => 'Standing Flower',        'kode' => 'BRG-DK-003', 'kategori' => 'Dekorasi',     'stok' => 6,  'satuan' => 'buah',  'kondisi' => 'baik',         'deskripsi' => 'Standing flower artificial untuk dekorasi'],

            // Alat Tulis & Kantor
            ['nama' => 'Whiteboard Portable',    'kode' => 'BRG-AK-001', 'kategori' => 'Alat Kantor',  'stok' => 3,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Papan tulis putih portable beroda'],
            ['nama' => 'Flipchart & Tripod',     'kode' => 'BRG-AK-002', 'kategori' => 'Alat Kantor',  'stok' => 2,  'satuan' => 'set',   'kondisi' => 'baik',         'deskripsi' => 'Flipchart dengan tripod dan kertas isi ulang'],
            ['nama' => 'Laminator A4',           'kode' => 'BRG-AK-003', 'kategori' => 'Alat Kantor',  'stok' => 1,  'satuan' => 'unit',  'kondisi' => 'rusak_ringan', 'deskripsi' => 'Mesin laminator A4, pemanasan lama'],
            ['nama' => 'Penghitung Suara (Tally Counter)', 'kode' => 'BRG-AK-004', 'kategori' => 'Alat Kantor', 'stok' => 5, 'satuan' => 'buah', 'kondisi' => 'baik', 'deskripsi' => 'Alat hitung manual untuk keperluan voting'],

            // Kebersihan
            ['nama' => 'Vacuum Cleaner',         'kode' => 'BRG-KB-001', 'kategori' => 'Kebersihan',   'stok' => 2,  'satuan' => 'unit',  'kondisi' => 'baik',         'deskripsi' => 'Vacuum cleaner untuk membersihkan ruangan setelah acara'],
        ];

        DB::table('barang')->insert(array_map(fn($b) => [
            ...$b,
            'foto'       => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $barang));

        $this->command->info('✅ BarangSeeder: ' . count($barang) . ' barang dibuat.');
    }
}
