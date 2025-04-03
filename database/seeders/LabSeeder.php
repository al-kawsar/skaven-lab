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

        Lab::create([
            'name' => 'Lab Jaringan Komputer',
            'facilities' => '25 unit komputer dengan spesifikasi Intel Core i5, RAM 8GB, SSD 256GB dan monitor 24 inci Full HD. Dilengkapi perangkat jaringan seperti router Cisco, switch manageable, server rack, patch panel dan crimping tools. Software jaringan terinstal seperti Cisco Packet Tracer, Wireshark, dan VirtualBox. Terdapat proyektor HD, AC split, akses internet dedicated 50 Mbps serta rak penyimpanan peralatan jaringan.',
            'status' => 'tersedia',
            'user_id' => User::first()->id
        ]);

        Lab::create([
            'name' => 'Lab Multimedia',
            'facilities' => '20 unit workstation editing dengan prosesor Intel Core i7, RAM 16GB, SSD 512GB, GPU RTX 2060 dan monitor ultrawide 34 inci. Software editing terinstal seperti Adobe Creative Suite, DaVinci Resolve dan OBS Studio. Dilengkapi kamera DSLR, lighting kit, green screen backdrop, audio interface, studio monitor speaker. Ruangan kedap suara dengan AC central dan proyektor 4K.',
            'status' => 'tidak tersedia',
            'user_id' => User::first()->id
        ]);

        Lab::create([
            'name' => 'Lab Akuntansi dan Keuangan',
            'facilities' => '30 unit komputer dengan spesifikasi Intel Core i5, RAM 8GB, SSD 256GB dan monitor 24 inci. Software akuntansi terinstal seperti MYOB, Accurate, Zahir Accounting. Dilengkapi printer kasir, scanner barcode, cash register dan peralatan penghitung uang. Ruangan ber-AC dengan proyektor HD dan papan tulis interaktif.',
            'status' => 'tersedia',
            'user_id' => User::first()->id
        ]);

        Lab::create([
            'name' => 'Lab Administrasi Perkantoran',
            'facilities' => '25 unit komputer dengan software perkantoran lengkap, mesin tik elektronik, mesin fotokopi multifungsi, scanner, printer laser, filing cabinet, telepon PABX, dan peralatan kearsipan. Ruangan dilengkapi AC split, proyektor HD dan meja resepsionis untuk praktik.',
            'status' => 'tersedia',
            'user_id' => User::first()->id
        ]);

        Lab::create([
            'name' => 'Lab Perawatan Sosial',
            'facilities' => 'Ruangan praktik dilengkapi bed pasien elektrik, manekin perawatan, peralatan medis dasar, kursi roda, walker, dan perlengkapan P3K lengkap. Tersedia area simulasi home care dengan kamar tidur dan kamar mandi khusus disabilitas. Ruangan ber-AC dengan loker penyimpanan dan area sterilisasi alat.',
            'status' => 'tersedia',
            'user_id' => User::first()->id
        ]);

        // \App\Models\User::factory()->count(10)->create();
        // \App\Models\File::factory()->count(5)->create();
        // for($i=1; $i < 1000; $i++){
        // Lab::factory(500)->create();
        // }
    }
}
