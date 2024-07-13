<?php

namespace Database\Seeders;

use App\Models\Lab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lab::create([
        //     'name' => 'Lab Rekayasa Perangkat Lunak',
        //     'location' => 'Samping Kelas X RPL',
        //     'capacity' => '2',
        //     'facilities' => '-',
        //     'status' => 'tersedia',
        // ]);
        // Lab::create([
        //     'name' => 'Lab Akuntansi dan Keuangan',
        //     'location' => 'Samping Kelas -',
        //     'capacity' => '1',
        //     'facilities' => '-',
        //     'status' => 'tersedia',
        // ]);
        // Lab::create([
        //     'name' => 'Lab Administrasi Perkantoran',
        //     'location' => 'Samping Kelas -',
        //     'capacity' => '1',
        //     'facilities' => '-',
        //     'status' => 'tersedia',
        // ]);
        // Lab::create([
        //     'name' => 'Lab Perawatan Sosial',
        //     'location' => 'Samping Kelas -',
        //     'capacity' => '1',
        //     'facilities' => '-',
        //     'status' => 'tersedia',
        // ]);
    
        // for($i=1; $i < 1000; $i++){
            Lab::factory(500)->create();
        // }
    }
}
