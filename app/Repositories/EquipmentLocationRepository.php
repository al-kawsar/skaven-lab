<?php

namespace App\Repositories;

use App\Models\EquipmentLocation;
use Illuminate\Database\Eloquent\Collection;

class EquipmentLocationRepository
{
    public function __construct(private EquipmentLocation $model)
    {
    }

    public function getFilteredData(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['code'])) {
            $query->where('code', 'like', '%' . $filters['code'] . '%');
        }

        if (!empty($filters['building'])) {
            $query->where('building', 'like', '%' . $filters['building'] . '%');
        }

        return $query->orderBy('name', 'asc')->get();
    }

    public function findById(int $id): ?EquipmentLocation
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): EquipmentLocation
    {
        return $this->model->create($data);
    }

    public function update(EquipmentLocation $location, array $data): bool
    {
        return $location->update($data);
    }

    public function delete(EquipmentLocation $location): bool
    {
        return $location->delete();
    }

    public function hasEquipment(EquipmentLocation $location): bool
    {
        return $location->equipment()->exists();
    }

    public function getEquipmentCount(EquipmentLocation $location): int
    {
        return $location->equipment()->count();
    }

    public function bulkDelete(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}