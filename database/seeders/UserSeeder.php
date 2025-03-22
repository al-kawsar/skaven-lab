<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'SuperAdmin - SMKN 7 Makassar',
            'email' => 'superadmin@belajar.id',
            'password' => bcrypt('123123123'),
            'role_id' => User::ROLE_SUPERADMIN
        ]);

        User::create([
            'name' => 'Staff Admin - SMKN 7 Makassar',
            'email' => 'admin@smk7.sch.id',
            'password' => bcrypt('123123123'),
            'role_id' => User::ROLE_ADMIN
        ]);

        User::create([
            'name' => 'Ibrahim Mallombassang',
            'email' => 'ibrahim@smk7.sch.id',
            'password' => bcrypt('123123123'),
            'role_id' => User::ROLE_TEACHER
        ]);

        User::create([
            'name' => 'siswa',
            'email' => 'andimuhraihan22355@smk7.sch.id',
            'password' => bcrypt('123123123'),
            'role_id' => User::ROLE_STUDENT
        ]);
    }
}
