<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Role;
use App\Models\Student;
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
            FileSeeder::class,
            UserSeeder::class,
            LabSeeder::class,
            SettingSeeder::class,
            EquipmentSeeder::class,
        ]);
        Student::factory(100)->create();
        Teacher::factory(100)->create();

        Student::create([
            'name' => 'Andi Muh Raihan Alkawsar',
            'nis' => '22355',
            'nisn' => '0077865020',
            'alamat' => 'Jl. Contoh Alamat',
            'jenis_kelamin' => 'l',
            'agama' => 'Islam',
            'tgl_lahir' => '08-01-2007',
            'foto_siswa' => null,
        ]);

    }
}
