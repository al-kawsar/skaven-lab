<?php

namespace App\Repositories;

use App\Models\EquipmentCategory;
use Illuminate\Database\Eloquent\Collection;

class EquipmentCategoryRepository
{
    public function __construct(private EquipmentCategory $model)
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

        return $query->orderBy('name', 'asc')->get();
    }

    public function findById(int $id): ?EquipmentCategory
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): EquipmentCategory
    {
        return $this->model->create($data);
    }

    public function update(EquipmentCategory $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(EquipmentCategory $category): bool
    {
        return $category->delete();
    }

    public function hasEquipment(EquipmentCategory $category): bool
    {
        return $category->equipment()->exists();
    }

    public function getEquipmentCount(EquipmentCategory $category): int
    {
        return $category->equipment()->count();
    }
}