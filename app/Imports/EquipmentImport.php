<?php

namespace App\Imports;

use App\Models\Equipment;
use App\Models\EquipmentLocation;
use App\Models\EquipmentCategory;
use App\Services\Inventory\InventoryCodeGenerator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Str;

class EquipmentImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    private $rowCount = 0;
    private $successCount = 0;
    private $failedCount = 0;
    private $failedRows = [];
    private $newLocations = [];
    private $newCategories = [];
    private $skipHeader;
    private $codeGenerator;

    public function __construct(bool $skipHeader = true)
    {
        $this->skipHeader = $skipHeader;
        $this->codeGenerator = new InventoryCodeGenerator();
    }

    private function getOrCreateLocation($locationName)
    {
        if (empty($locationName))
            return null;

        // Cari lokasi yang sudah ada
        $location = EquipmentLocation::where('name', $locationName)->first();

        if (!$location) {
            // Jika lokasi belum ada di database dan belum pernah dibuat sebelumnya
            if (!isset($this->newLocations[$locationName])) {
                // Generate kode lokasi
                $code = 'LOK' . str_pad(EquipmentLocation::count() + count($this->newLocations) + 1, 3, '0', STR_PAD_LEFT);

                // Buat lokasi baru
                $location = EquipmentLocation::create([
                    'name' => $locationName,
                    'code' => $code,
                    'description' => 'Dibuat otomatis melalui import'
                ]);

                // Simpan ke cache untuk digunakan baris berikutnya
                $this->newLocations[$locationName] = $location;
            } else {
                // Gunakan lokasi yang sudah dibuat sebelumnya
                $location = $this->newLocations[$locationName];
            }
        }

        return $location;
    }

    private function getOrCreateCategory($categoryName)
    {
        if (empty($categoryName))
            return null;

        // Cari kategori yang sudah ada
        $category = EquipmentCategory::where('name', $categoryName)->first();

        if (!$category) {
            // Jika kategori belum ada dan belum pernah dibuat sebelumnya
            if (!isset($this->newCategories[$categoryName])) {
                // Generate kode kategori
                $code = 'CAT' . str_pad(EquipmentCategory::count() + count($this->newCategories) + 1, 3, '0', STR_PAD_LEFT);

                // Buat kategori baru
                $category = EquipmentCategory::create([
                    'name' => $categoryName,
                    'code' => $code,
                    'description' => 'Dibuat otomatis melalui import'
                ]);

                // Simpan ke cache
                $this->newCategories[$categoryName] = $category;
            } else {
                // Gunakan kategori yang sudah dibuat sebelumnya
                $category = $this->newCategories[$categoryName];
            }
        }

        return $category;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        try {
            // Dapatkan atau buat lokasi dan kategori baru jika ada
            $location = !empty($row['lokasi']) ? $this->getOrCreateLocation($row['lokasi']) : null;
            $category = !empty($row['kategori']) ? $this->getOrCreateCategory($row['kategori']) : null;

            // Generate kode barang otomatis
            $code = $this->codeGenerator->generateEquipmentCode($category?->id);

            $data = [
                'name' => $row['nama_barang'],
                'code' => $code,
                'stock' => $row['stok'],
                'condition' => strtolower($row['kondisi']),
                'category_id' => $category?->id,
                'location_id' => $location?->id,
                'description' => $row['deskripsi'] ?? null
            ];

            $this->successCount++;
            return new Equipment($data);

        } catch (\Exception $e) {
            $this->failedCount++;
            $this->failedRows[] = [
                'row' => $this->rowCount + 1,
                'data' => $row,
                'reason' => $e->getMessage()
            ];
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'kondisi' => ['required', 'string', 'in:baik,rusak ringan,rusak berat,BAIK,RUSAK RINGAN,RUSAK BERAT'],
            'kategori' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter',
            'stok.required' => 'Stok harus diisi',
            'stok.integer' => 'Stok harus berupa angka',
            'stok.min' => 'Stok minimal 0',
            'kondisi.required' => 'Kondisi harus diisi',
            'kondisi.in' => 'Kondisi harus diisi dengan: baik, rusak ringan, atau rusak berat',
            'kategori.max' => 'Nama kategori maksimal 255 karakter',
            'lokasi.max' => 'Nama lokasi maksimal 255 karakter'
        ];
    }

    public function withHeadingRow()
    {
        return $this->skipHeader ? 0 : false;
    }

    // Getter methods
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getFailedCount(): int
    {
        return $this->failedCount;
    }

    public function getFailedRows(): array
    {
        return $this->failedRows;
    }

    // Tambahkan method untuk mendapatkan info lokasi baru
    public function getNewLocations(): array
    {
        return $this->newLocations;
    }

    public function getNewCategories(): array
    {
        return $this->newCategories;
    }
}