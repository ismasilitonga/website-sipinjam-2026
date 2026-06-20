<?php

namespace Database\Seeders;

use App\Models\Ormawa;
use Illuminate\Database\Seeder;

class OrmawaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ormawas = [
            ['singkatan' => 'dpm',            'nama_organisasi' => 'DPM'],
            ['singkatan' => 'bem',            'nama_organisasi' => 'BEM'],
            ['singkatan' => 'hmti',           'nama_organisasi' => 'HMTI'],
            ['singkatan' => 'hme',            'nama_organisasi' => 'HME'],
            ['singkatan' => 'hmm',            'nama_organisasi' => 'HMM'],
            ['singkatan' => 'hmmb',           'nama_organisasi' => 'HMMB'],
            ['singkatan' => 'pd-elshaddai',   'nama_organisasi' => 'PD El-Shaddai'],
            ['singkatan' => 'immpb',          'nama_organisasi' => 'IMMPB'],
            ['singkatan' => 'menwa',          'nama_organisasi' => 'MENWA'],
            ['singkatan' => 'mapala',         'nama_organisasi' => 'MAPALA'],
            ['singkatan' => 'pec',            'nama_organisasi' => 'PEC'],
            ['singkatan' => 'kuas',           'nama_organisasi' => 'KUAS'],
            ['singkatan' => 'blug',           'nama_organisasi' => 'BLUG'],
            ['singkatan' => 'lpm-paradigma',  'nama_organisasi' => 'LPM Paradigma'],
            ['singkatan' => 'energi',         'nama_organisasi' => 'ENERGI'],
            ['singkatan' => 'kop',            'nama_organisasi' => 'KOP'],
        ];

        foreach ($ormawas as $data) {
            Ormawa::updateOrCreate(
                ['singkatan' => $data['singkatan']],
                [
                    'nama_organisasi' => $data['nama_organisasi'],
                    'kontak'          => null,
                    'deskripsi'       => null,
                    'status'          => 'aktif',
                ]
            );
        }
    }
}