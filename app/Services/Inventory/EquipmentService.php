<?php

namespace App\Services\Inventory;

use App\DTOs\Inventory\EquipmentData;
use App\Exceptions\EquipmentException;
use App\Models\Equipment;
use App\Repositories\EquipmentRepository;
use App\Services\FileService;
use App\Transformers\EquipmentTransformer;
use Illuminate\Support\Facades\DB;
use App\Exports\EquipmentExport;
use App\Imports\EquipmentImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EquipmentService
{
    public function __construct(
        private EquipmentRepository $repository,
        private FileService $fileService,
        private InventoryCodeGenerator $codeGenerator,
        private EquipmentTransformer $transformer
    ) {
    }

    public function getDashboardData(): array
    {
        $statistics = $this->repository->getStatistics();
        // $categories = \App\Models\EquipmentCategory::orderBy('name')->get();
        // $locations = \App\Models\EquipmentLocation::orderBy('name')->get();

        return [
            'totalData' => $statistics,
            // 'categories' => $categories,
            // 'locations' => $locations
        ];
    }

    public function getDataTableData($request): array
    {
        $data = $this->repository->getFilteredData($request);

        return [
            'draw' => $request->draw,
            'recordsTotal' => $data->total(),
            'recordsFiltered' => $data->total(),
            'data' => $data->map(fn($item) => $this->transformer->transform($item)),
            'meta' => [
                'total' => $data->total(),
                'goodCondition' => Equipment::where('condition', 'baik')->count(),
                'minorDamage' => Equipment::where('condition', 'rusak ringan')->count(),
                'majorDamage' => Equipment::where('condition', 'rusak berat')->count(),
                'totalStock' => Equipment::sum('stock')
            ]
        ];
    }

    public function getEquipmentDetail(Equipment $equipment): array
    {
        return $this->transformer->transform($equipment);
    }

    public function store(EquipmentData $data)
    {
        try {
            DB::beginTransaction();

            if ($data->image) {
                $file = $this->fileService->uploadFile($data->image);
            }

            $equipment = $this->repository->create([
                'name' => $data->name,
                'code' => $data->code ?: $this->codeGenerator->generateEquipmentCode($data->category_id),
                'stock' => $data->stock,
                'condition' => $data->condition,
                'category_id' => $data->category_id,
                'location_id' => $data->location_id,
                'description' => $data->description,
                'file_id' => $file->id ?? null,
            ]);

            DB::commit();
            return $equipment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw EquipmentException::failedToCreate($e->getMessage());
        }
    }

    public function update(Equipment $equipment, EquipmentData $data)
    {
        try {
            DB::beginTransaction();

            if ($data->image) {
                $file = $this->fileService->uploadFile($data->image);
                if ($equipment->file_id) {
                    $this->fileService->deleteFileById($equipment->file_id);
                }
            }

            $equipment = $this->repository->update($equipment, [
                'name' => $data->name,
                'code' => $data->code ?: $this->codeGenerator->generateEquipmentCode($data->category_id),
                'stock' => $data->stock,
                'condition' => $data->condition,
                'category_id' => $data->category_id,
                'location_id' => $data->location_id,
                'description' => $data->description,
                'file_id' => $file->id ?? $equipment->file_id,
            ]);

            DB::commit();
            return $equipment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw EquipmentException::failedToUpdate($e->getMessage());
        }
    }

    public function delete(Equipment $equipment): bool
    {
        try {
            DB::beginTransaction();

            if ($equipment->file_id) {
                $this->fileService->deleteFileById($equipment->file_id);
            }

            $result = $this->repository->delete($equipment);

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new EquipmentException("Gagal menghapus barang: " . $e->getMessage());
        }
    }

    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($ids as $id) {
                $equipment = $this->repository->findById($id);
                if ($equipment && $this->delete($equipment)) {
                    $count++;
                }
            }

            DB::commit();
            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new EquipmentException("Gagal menghapus barang: " . $e->getMessage());
        }
    }

    public function deleteAll(): bool
    {
        try {
            DB::beginTransaction();

            // Ambil semua equipment yang memiliki file
            $equipments = Equipment::whereNotNull('file_id')->with('file')->get();

            // Hapus file terkait
            foreach ($equipments as $equipment) {
                if ($equipment->file_id) {
                    $this->fileService->deleteFileById($equipment->file_id);
                }
            }

            // Hapus semua equipment
            $this->repository->deleteAll();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new EquipmentException("Gagal menghapus semua barang: " . $e->getMessage());
        }
    }

    /**
     * Export data equipment berdasarkan tipe file
     */
    public function export(string $type): BinaryFileResponse
    {
        $filename = 'data-barang-' . date('Y-m-d-His');

        return match ($type) {
            'xlsx' => Excel::download(new EquipmentExport, $filename . '.xlsx'),
            'csv' => Excel::download(new EquipmentExport, $filename . '.csv'),
            'pdf' => Excel::download(new EquipmentExport, $filename . '.pdf'),
            default => throw new EquipmentException("Format file tidak didukung")
        };
    }

    /**
     * Download template import
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $filename = 'template-import-barang.xlsx';
        return Excel::download(new EquipmentExport(true), $filename);
    }

    /**
     * Import data dari file Excel/CSV
     */
    public function import($request)
    {
        try {
            DB::beginTransaction();

            // Validasi file
            if (!$request->hasFile('import_file')) {
                throw new EquipmentException("File import tidak ditemukan");
            }

            $file = $request->file('import_file');

            // Validasi ekstensi file
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, ['xlsx', 'csv'])) {
                throw new EquipmentException("Format file harus xlsx atau csv");
            }

            // Validasi ukuran file (max 2MB)
            if ($file->getSize() > 2048000) {
                throw new EquipmentException("Ukuran file maksimal 2MB");
            }

            // Import data
            $import = new EquipmentImport(
                skipHeader: $request->boolean('skip_header', true)
            );

            Excel::import($import, $file);

            $result = [
                'total_rows' => $import->getRowCount(),
                'success_count' => $import->getSuccessCount(),
                'failed_count' => $import->getFailedCount(),
                'failed_rows' => $import->getFailedRows(),
                'new_locations' => array_values($import->getNewLocations()),
                'new_categories' => array_values($import->getNewCategories())
            ];

            if ($result['failed_count'] > 0) {
                DB::rollBack();
                throw new EquipmentException(
                    "Terdapat {$result['failed_count']} baris data yang gagal diimport. " .
                    "Silahkan periksa kembali data Anda."
                );
            }

            DB::commit();

            // Tambahkan informasi tentang data baru yang dibuat
            $messages = [];
            if (count($result['new_categories']) > 0) {
                $messages[] = count($result['new_categories']) . " kategori baru telah dibuat";
            }
            if (count($result['new_locations']) > 0) {
                $messages[] = count($result['new_locations']) . " lokasi baru telah dibuat";
            }

            if (!empty($messages)) {
                $result['message'] = "Import berhasil. " . implode(" dan ", $messages) . ".";
            } else {
                $result['message'] = "Import berhasil.";
            }

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new EquipmentException("Gagal mengimport data: " . $e->getMessage());
        }
    }
}