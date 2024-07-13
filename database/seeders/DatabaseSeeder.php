<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            LabSeeder::class,
        ]);
        Siswa::factory(100)->create();
        Guru::factory(100)->create();

        User::create([
            'name' => 'SMK 7 Makassar',
            'email' => 'admin@smk7.com',
            'password' => bcrypt('rahasia123'),
            'role_id' => 1
        ]);
        User::create([
            'name' => 'SMK 7 Makassar',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123123123'),
            'role_id' => 4
        ]);

        Siswa::create([
            'name' => 'Andi Muh Raihan Alkawsar',
            'nis' => '22355',
            'nisn' => '0077865020',
            'alamat' => 'Jl. Contoh Alamat',
            'jenis_kelamin' => 'l',
            'agama' => 'Islam',
            'tgl_lahir' => '08-15-2005',
            'foto_siswa' => null,
        ]);

    }
}
