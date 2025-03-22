<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lab::create([
            'name' => 'Lab RPL',
            'facilities' => '30 unit komputer dengan prosesor Intel Core i5, RAM 8GB, dan SSD 256GB, dilengkapi dengan monitor 24 inci Full HD. Setiap komputer sudah terinstal sistem operasi Windows 10 dan Linux Ubuntu, serta berbagai software pemrograman seperti Visual Studio Code, XAMPP, Android Studio, dan Git. Terdapat 1 unit proyektor Full HD dengan layar proyeksi 100 inci untuk presentasi dan pembelajaran interaktif. Ruangan dilengkapi dengan 2 unit AC, papan whiteboard besar, dan akses internet Wi-Fi berkecepatan 100 Mbps. Selain itu, tersedia 10 unit headset untuk keperluan multimedia serta sebuah lemari penyimpanan khusus untuk barang pribadi siswa.',
            'status' => 'tersedia',
            'user_id' => User::first()->id
        ]);

        // Lab::create([
        //     'name' => 'Lab Jaringan Komputer',
        //     'capacity' => 25,
        //     'facilities' => 'Router, Switch, Server, Komputer, Internet',
        //     'status' => 'tersedia',
        //     'owner_id' => User::first()->id
        // ]);

        // Lab::create([
        //     'name' => 'Lab Multimedia',
        //     'capacity' => 20,
        //     'facilities' => 'PC Editing, Kamera, Green Screen, Speaker, Proyektor',
        //     'status' => 'tidak tersedia',
        //     'owner_id' => User::first()->id
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
        // \App\Models\User::factory()->count(10)->create();
        // \App\Models\File::factory()->count(5)->create();
        // for($i=1; $i < 1000; $i++){
        // Lab::factory(500)->create();
        // }
    }
}
