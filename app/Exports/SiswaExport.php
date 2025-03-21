<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $siswa;

    public function __construct($siswa)
    {
        $this->siswa = $siswa;
    }

    public function collection()
    {
        return $this->siswa;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'NIS',
            'NISN',
            'Alamat',
            'Jenis Kelamin',
            'Agama',
            'Tanggal Lahir',
            'Usia',
            'Tanggal Ditambahkan'
        ];
    }

    public function map($siswa): array
    {
        return [
            $siswa->id,
            $siswa->name,
            $siswa->nis,
            $siswa->nisn,
            $siswa->alamat,
            $siswa->jenis_kelamin == 'l' ? 'Laki-laki' : 'Perempuan',
            $siswa->agama,
            Carbon::parse($siswa->tgl_lahir)->format('d-m-Y'),
            Carbon::parse($siswa->tgl_lahir)->age . ' tahun',
            $siswa->created_at->format('d-m-Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
