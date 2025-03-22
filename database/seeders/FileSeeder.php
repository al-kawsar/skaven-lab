<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\FileService;
use Illuminate\Http\UploadedFile;


class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //     $fileService = new FileService();
    //     // Ambil file dari URL dan buat file sementara
    //     $fileContents = file_get_contents('http://lab-managament.test/assets/img/profile.jpg');
    //     $tempPath = sys_get_temp_dir() . '/profile.jpg'; // Simpan di folder sementara
    //     file_put_contents($tempPath, $fileContents);

    //     // Buat instance UploadedFile dari file sementara
    //     $uploadedFile = new UploadedFile($tempPath, 'profile.jpg');

    //     // Panggil service untuk upload
    //     $fileService->uploadFile($uploadedFile, 'lab');
    }
}
