<?php

namespace App\Repositories;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Collection;

class LabRepository
{
    public function __construct(private Lab $model)
    {
    }

    public function getFilteredData(array $filters = []): Collection
    {
        $query = $this->model->with('users');

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['facilities'])) {
            $query->where('facilities', 'like', '%' . $filters['facilities'] . '%');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getStatistics(): array
    {
        return $this->model->selectRaw("
            COUNT(*) as totalData,
            SUM(status = 'tersedia') as totalAvailable,
            SUM(status = 'tidak tersedia') as totalUnavailable
        ")->first()->toArray();
    }

    public function findById(string $id): ?Lab
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Lab
    {
        return $this->model->create($data);
    }

    public function update(Lab $lab, array $data): bool
    {
        return $lab->update($data);
    }

    public function delete(Lab $lab): bool
    {
        return $lab->delete();
    }

    public function deleteAll(): bool
    {
        return $this->model->query()->delete() > 0;
    }
}