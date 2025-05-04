<?php

namespace App\Repositories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EquipmentRepository
{
    public function __construct(private Equipment $model)
    {
    }

    public function getStatistics(): array
    {
        return $this->model->selectRaw("
            COUNT(*) as totalData,
            SUM(`condition` = 'baik') as totalGoodCondition,
            SUM(`condition` = 'rusak ringan') as totalMinorDamage,
            SUM(`condition` = 'rusak berat') as totalMajorDamage,
            SUM(stock) as totalStock
        ")->first()->toArray();
    }

    public function getFilteredData($request): LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'location', 'file']);

        // Apply search filter
        if ($request->search['value']) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('code', 'like', "%{$searchValue}%")
                    ->orWhereHas('category', function ($q) use ($searchValue) {
                        $q->where('name', 'like', "%{$searchValue}%");
                    })
                    ->orWhereHas('location', function ($q) use ($searchValue) {
                        $q->where('name', 'like', "%{$searchValue}%");
                    });
            });
        }

        // Apply specific filters
        $query->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->code, fn($q) => $q->where('code', 'like', "%{$request->code}%"))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->location_id, fn($q) => $q->where('location_id', $request->location_id))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition));

        // Apply ordering
        $orderColumn = $request->order[0]['column'] ?? 2;
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        $columns = [
            0 => 'id', // For select checkbox column
            1 => 'id', // For select checkbox column
            2 => 'name',
            3 => 'code',
            4 => 'stock',
            5 => 'category_id',
            6 => 'location_id',
            7 => 'condition'
        ];

        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        }

        // Always order by updated_at as secondary sort
        $query->orderBy('updated_at', 'desc');

        return $query->paginate($request->length);
    }

    public function create(array $data): Equipment
    {
        return $this->model->create($data);
    }

    public function update(Equipment $equipment, array $data): bool
    {
        return $equipment->update($data);
    }

    public function delete(Equipment $equipment): bool
    {
        return $equipment->delete();
    }

    public function deleteAll(): bool
    {
        return $this->model->query()->delete() > 0;
    }

    public function findById(string|int $id): Equipment
    {
        return $this->model->with(['category', 'location', 'file'])->find($id);
    }

    public function bulkDelete(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}