<?php

namespace App\Services\Inventory;

use App\DTOs\Inventory\CategoryData;
use App\Exceptions\CategoryException;
use App\Repositories\EquipmentCategoryRepository;
use App\Transformers\EquipmentCategoryTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EquipmentCategoryService
{
    public function __construct(
        private EquipmentCategoryRepository $repository,
        private EquipmentCategoryTransformer $transformer
    ) {
    }

    public function getDataTableData($request): array
    {
        $filters = [
            'name' => $request->input('name'),
            'code' => $request->input('code')
        ];

        $categories = $this->repository->getFilteredData($filters);

        $counter = 0;
        $transformedData = $categories->map(
            fn($category) =>
            $this->transformer->transform($category, ++$counter)
        );

        return [
            'data' => $transformedData,
            'meta' => [
                'total' => $categories->count(),
            ],
        ];
    }

    public function store(CategoryData $data): array
    {
        try {
            $this->validateCategory($data);

            DB::beginTransaction();

            $category = $this->repository->create([
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'color' => $data->color,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $this->transformer->transform($category)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CategoryException('Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    public function update(int $id, CategoryData $data): array
    {
        try {
            $category = $this->repository->findById($id);
            $this->validateCategory($data, $id);

            DB::beginTransaction();

            $this->repository->update($category, [
                'name' => $data->name,
                'code' => $data->code,
                'description' => $data->description,
                'color' => $data->color,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $this->transformer->transform($category)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CategoryException('Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        try {
            $category = $this->repository->findById($id);

            if ($this->repository->hasEquipment($category)) {
                throw new CategoryException(
                    'Kategori tidak dapat dihapus karena sedang digunakan oleh ' .
                    $this->repository->getEquipmentCount($category) . ' barang.'
                );
            }

            DB::beginTransaction();
            $this->repository->delete($category);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CategoryException('Gagal menghapus kategori: ' . $e->getMessage());
        }
    }

    public function findById(int $id): array
    {
        try {
            $category = $this->repository->findById($id);
            return [
                'success' => true,
                'data' => $this->transformer->transform($category)
            ];
        } catch (\Exception $e) {
            throw new CategoryException('Kategori tidak ditemukan');
        }
    }

    private function validateCategory(CategoryData $data, ?int $id = null): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:equipment_categories,code' . ($id ? ',' . $id : ''),
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ];

        $validator = Validator::make((array) $data, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }
}