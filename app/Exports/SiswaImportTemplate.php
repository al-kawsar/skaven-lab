<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;

class SiswaImportTemplate implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        // Contoh data
        return [
            [
                'Nama Siswa',
                '123456',
                '1234567890',
                'Alamat Siswa',
                'Laki-laki',
                'Islam',
                '01-01-2000'
            ],
            [
                'Nama Siswa 2',
                '654321',
                '0987654321',
                'Alamat Siswa 2',
                'Perempuan',
                'Kristen',
                '02-02-2001'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'nis',
            'nisn',
            'alamat',
            'jenis_kelamin',
            'agama',
            'tanggal_lahir',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A' => ['font' => ['bold' => true]],
        ];
    }
}