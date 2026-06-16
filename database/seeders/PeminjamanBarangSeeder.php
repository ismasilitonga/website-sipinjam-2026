<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PeminjamanBarangSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $anggotaId = User::where('email', 'isma@gmail.com')->value('id');
        $picId     = User::where('email', 'pic.dpm@gmail.com')->value('id');

        $barang = fn(string $kode) => DB::table('barangs')->where('kode', $kode)->value('id');

        $peminjaman_barangs = [

        [
                'user_id'                 => $anggotaId,
                'barang_id'               => $barang('BRG-EL-001'),
                'nama_ormawa'             => 'KUAS',
                'jumlah'                  => 2,
                'tanggal_pinjam'          => now()->subDays(7)->toDateString(),
                'tanggal_kembali_rencana' => now()->subDays(6)->toDateString(),
                'keperluan'               => 'Presentasi laporan pertanggungjawaban kegiatan',
                'status'                  => 'disetujui',
                'waktu_diserahkan'           => now()->subDays(7)->setTime(9, 0),
                'diserahkan_oleh'         => $picId,
                'waktu_diterima_kembali'     => now()->subDays(6)->setTime(15, 0),
                'diterima_oleh'           => $picId,
                'alasan_tolak'            => null,
            ],


            [
                'user_id'                 => $anggotaId,
                'barang_id'               => $barang('BRG-EL-003'),
                'nama_ormawa'             => 'KUAS',
                'jumlah'                  => 2,
                'tanggal_pinjam'          => now()->toDateString(),
                'tanggal_kembali_rencana' => now()->addDays(1)->toDateString(),
                'keperluan'               => 'Workshop IoT — sound system untuk presentasi',
                'status'                  => 'disetujui',
                'waktu_diserahkan'           => now()->setTime(8, 45),
                'diserahkan_oleh'         => $picId,
                'waktu_diterima_kembali'     => null,
                'diterima_oleh'           => null,
                'alasan_tolak'            => null,
            ],


            [
                'user_id'                 => $anggotaId,
                'barang_id'               => $barang('BRG-DK-001'),
                'nama_ormawa'             => 'KUAS',
                'jumlah'                  => 3,
                'tanggal_pinjam'          => now()->addDays(5)->toDateString(),
                'tanggal_kembali_rencana' => now()->addDays(6)->toDateString(),
                'keperluan'               => 'Pameran karya seni outdoor di pelataran kampus',
                'status'                  => 'menunggu_ketua',
                'waktu_diserahkan'           => null,
                'diserahkan_oleh'         => null,
                'waktu_diterima_kembali'     => null,
                'diterima_oleh'           => null,
                'alasan_tolak'            => null,
            ],


            [
                'user_id'                 => $anggotaId,
                'barang_id'               => $barang('BRG-EL-004'),
                'nama_ormawa'             => 'KUAS',
                'jumlah'                  => 3,
                'tanggal_pinjam'          => now()->addDays(2)->toDateString(),
                'tanggal_kembali_rencana' => now()->addDays(3)->toDateString(),
                'keperluan'               => 'Seminar open source — mic untuk sesi tanya jawab',
                'status'                  => 'menunggu_pic',
                'waktu_diserahkan'           => null,
                'diserahkan_oleh'         => null,
                'waktu_diterima_kembali'     => null,
                'diterima_oleh'           => null,
                'alasan_tolak'            => null,
            ],


            [
                'user_id'                 => $anggotaId,
                'barang_id'               => $barang('BRG-EL-007'),
                'nama_ormawa'             => 'KUAS',
                'jumlah'                  => 1,
                'tanggal_pinjam'          => now()->subDays(2)->toDateString(),
                'tanggal_kembali_rencana' => now()->subDays(1)->toDateString(),
                'keperluan'               => 'Acara malam keakraban',
                'status'                  => 'ditolak',
                'alasan_tolak'            => 'Barang sedang dalam kondisi rusak ringan.',
                'waktu_diserahkan'           => null,
                'diserahkan_oleh'         => null,
                'waktu_diterima_kembali'     => null,
                'diterima_oleh'           => null,
            ],
        ];

        foreach ($peminjaman_barangs as $data) {
            DB::table('peminjaman_barangs')->insert([
                ...$data,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ PeminjamanBarangSeeder: ' . count($peminjaman_barangs) . ' peminjaman barang dibuat.');
    }
}