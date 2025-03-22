<?php

namespace App\Imports;

use App\Models\Siswa;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SiswaImport
{
    public function import($filePath)
    {
        try {
            // Directly use PhpSpreadsheet to read the file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            // Get all data including headers
            $data = $worksheet->toArray();

            // Check if we have data
            if (empty($data) || count($data) < 2) {
                throw new \Exception("File tidak berisi data yang cukup");
            }

            // Get headers from the first row
            $headers = array_map('strtolower', $data[0]);

            // Create a map of expected column names to their positions
            $columnMap = $this->mapColumns($headers);

            // Validate that all required columns exist
            $this->validateColumns($columnMap);

            // Process each row (skip header row)
            $successCount = 0;
            for ($i = 1; $i < count($data); $i++) {
                $row = $data[$i];

                // Skip if all values in row are empty
                if ($this->isEmptyRow($row))
                    continue;

                // Map data using column positions
                $siswaData = $this->mapRowToModel($row, $columnMap);

                // Create the model
                Siswa::create($siswaData);
                $successCount++;
            }

            return $successCount;
        } catch (\Exception $e) {
            Log::error("Direct import error: " . $e->getMessage());
            throw $e;
        }
    }

    private function mapColumns(array $headers): array
    {
        $columnMap = [
            'nama_lengkap' => null,
            'nis' => null,
            'nisn' => null,
            'alamat' => null,
            'jenis_kelamin' => null,
            'agama' => null,
            'tanggal_lahir' => null,
        ];

        foreach ($headers as $index => $header) {
            $header = trim(strtolower($header));
            if (array_key_exists($header, $columnMap)) {
                $columnMap[$header] = $index;
            }
        }

        return $columnMap;
    }

    private function validateColumns(array $columnMap): void
    {
        $required = ['nama_lengkap', 'nis', 'nisn', 'jenis_kelamin'];
        $missing = [];

        foreach ($required as $column) {
            if ($columnMap[$column] === null) {
                $missing[] = $column;
            }
        }

        if (!empty($missing)) {
            throw new \Exception("Kolom wajib tidak ditemukan: " . implode(", ", $missing));
        }
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (!empty($value)) {
                return false;
            }
        }
        return true;
    }

    private function mapRowToModel(array $row, array $columnMap): array
    {
        $tglLahir = null;
        if ($columnMap['tanggal_lahir'] !== null && !empty($row[$columnMap['tanggal_lahir']])) {
            $rawDate = $row[$columnMap['tanggal_lahir']];
            try {
                $tglLahir = Carbon::createFromFormat('d-m-Y', $rawDate)->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    $tglLahir = Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tglLahir = null;
                }
            }
        }

        $jenisKelamin = 'p'; // default
        if ($columnMap['jenis_kelamin'] !== null && !empty($row[$columnMap['jenis_kelamin']])) {
            $gender = strtolower($row[$columnMap['jenis_kelamin']]);
            $jenisKelamin = ($gender == 'laki-laki' || $gender == 'l') ? 'l' : 'p';
        }

        return [
            'name' => $row[$columnMap['nama_lengkap']] ?? '',
            'nis' => $row[$columnMap['nis']] ?? '',
            'nisn' => $row[$columnMap['nisn']] ?? '',
            'alamat' => ($columnMap['alamat'] !== null) ? ($row[$columnMap['alamat']] ?? null) : null,
            'jenis_kelamin' => $jenisKelamin,
            'agama' => ($columnMap['agama'] !== null) ? ($row[$columnMap['agama']] ?? null) : null,
            'tgl_lahir' => $tglLahir,
            'foto_siswa' => null,
        ];
    }
}
