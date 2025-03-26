<?php

namespace App\Services\Inventory;

use App\DTOs\Inventory\LocationData;
use App\Exceptions\LocationException;
use App\Repositories\EquipmentLocationRepository;
use App\Transformers\EquipmentLocationTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EquipmentLocationService
{
    public function __construct(
        private EquipmentLocationRepository $repository,
        private EquipmentLocationTransformer $transformer
    ) {
    }

    public function getDataTableData($request): array
    {
        $filters = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'building' => $request->input('building')
        ];

        $locations = $this->repository->getFilteredData($filters);

        $counter = 0;
        $transformedData = $locations->map(
            fn($location) =>
            $this->transformer->transform($location, ++$counter)
        );

        return [
            'data' => $transformedData,
            'meta' => [
                'total' => $locations->count(),
            ],
        ];
    }

    public function findById(int $id): array
    {
        $location = $this->repository->findById($id);
        return $this->transformer->transform($location);
    }

    public function store(LocationData $data): array
    {
        try {
            $this->validateLocation($data);

            DB::beginTransaction();

            $location = $this->repository->create([
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'building' => $data->building,
                'floor' => $data->floor,
                'room' => $data->room,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Lokasi berhasil ditambahkan',
                'data' => $this->transformer->transform($location)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw LocationException::failedToCreate($e->getMessage());
        }
    }

    public function update(int $id, LocationData $data): array
    {
        try {
            $location = $this->repository->findById($id);
            $this->validateLocation($data, $id);

            DB::beginTransaction();

            $this->repository->update($location, [
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'building' => $data->building,
                'floor' => $data->floor,
                'room' => $data->room,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui',
                'data' => $this->transformer->transform($location)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw LocationException::failedToUpdate($e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        try {
            $location = $this->repository->findById($id);

            if ($this->repository->hasEquipment($location)) {
                throw new LocationException(
                    'Lokasi tidak dapat dihapus karena sedang digunakan oleh ' .
                    $this->repository->getEquipmentCount($location) . ' barang.'
                );
            }

            DB::beginTransaction();
            $this->repository->delete($location);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Lokasi berhasil dihapus'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new LocationException('Gagal menghapus lokasi: ' . $e->getMessage());
        }
    }

    public function bulkDelete(array $ids): array
    {
        try {
            DB::beginTransaction();
            $count = $this->repository->bulkDelete($ids);
            DB::commit();

            return [
                'success' => true,
                'message' => $count . ' lokasi berhasil dihapus'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new LocationException('Gagal menghapus lokasi: ' . $e->getMessage());
        }
    }

    private function validateLocation(LocationData $data, ?int $id = null): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:equipment_locations,code' . ($id ? ',' . $id : ''),
            'description' => 'nullable|string',
            'building' => 'nullable|string|max:100',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
        ];

        $validator = Validator::make((array) $data, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }
}