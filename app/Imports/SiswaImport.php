<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Format tanggal dari Excel (jika formatnya DD-MM-YYYY)
        $tglLahir = null;
        if (!empty($row['tanggal_lahir'])) {
            try {
                $tglLahir = Carbon::createFromFormat('d-m-Y', $row['tanggal_lahir'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Coba format lain jika format pertama gagal
                try {
                    $tglLahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $tglLahir = null;
                }
            }
        }

        return new Siswa([
            'name' => $row['nama_lengkap'],
            'nis' => $row['nis'],
            'nisn' => $row['nisn'],
            'alamat' => $row['alamat'] ?? null,
            'jenis_kelamin' => strtolower($row['jenis_kelamin']) == 'laki-laki' ? 'l' : 'p',
            'agama' => $row['agama'] ?? null,
            'tgl_lahir' => $tglLahir,
            'foto_siswa' => null,
        ]);
    }


    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|max:255',
            'nisn' => 'required|string|max:255',
            'jenis_kelamin' => 'required',
        ];
    }

    /**
     * Custom pesan validasi
     */
    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Kolom nama lengkap wajib diisi',
            'nis.required' => 'Kolom NIS wajib diisi',
            'nisn.required' => 'Kolom NISN wajib diisi',
            'jenis_kelamin.required' => 'Kolom jenis kelamin wajib diisi',
        ];
    }
}
