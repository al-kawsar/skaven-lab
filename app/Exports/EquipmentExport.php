<?php

namespace App\Exports;

use App\Models\Equipment;
use App\Models\EquipmentLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $isTemplate;

    public function __construct(bool $isTemplate = false)
    {
        $this->isTemplate = $isTemplate;
    }

    public function collection()
    {
        if ($this->isTemplate) {
            return collect([]);
        }
        return Equipment::with(['location'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Barang *',
            'Stok *',
            'Kondisi *',
            'Kategori',
            'Lokasi',
            'Deskripsi'
        ];
    }

    public function map($equipment): array
    {
        return [
            $equipment->name,
            $equipment->stock,
            $equipment->condition,
            $equipment->category?->name,
            $equipment->location?->name,
            $equipment->description
        ];
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->isTemplate) {
            // Tambahkan validasi untuk kolom Kondisi
            $kondisiList = ['baik', 'rusak ringan', 'rusak berat'];
            $sheet->getCell('C2')->getDataValidation()
                ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setFormula1('"' . implode(',', $kondisiList) . '"');

            // Tambahkan catatan pada template
            $sheet->setCellValue('A7', 'Catatan:');
            $sheet->setCellValue('A8', '- Kolom dengan tanda * wajib diisi');
            $sheet->setCellValue('A9', '- Kondisi harus diisi dengan: baik, rusak ringan, atau rusak berat');
            $sheet->setCellValue('A10', '- Kategori dan Lokasi bersifat opsional');
            $sheet->setCellValue('A11', '- Kategori dan Lokasi bisa diisi dengan data yang sudah ada atau baru');
            $sheet->setCellValue('A12', '- Stok harus berupa angka');
        }

        return [
            1 => ['font' => ['bold' => true]],
            'A7:A12' => ['font' => ['italic' => true]],
        ];
    }
}