<?php

namespace App\Services\Lab;

use App\DTOs\Lab\LabData;
use App\Exceptions\LabException;
use App\Models\Lab;
use App\Repositories\LabRepository;
use App\Services\FileService;
use App\Transformers\LabTransformer;
use Illuminate\Support\Facades\DB;
use App\Traits\Lab\ImageHandlerTrait;
use App\Traits\Lab\LabSliderTrait;
use App\Traits\Lab\CleanupTrait;
use Illuminate\Http\UploadedFile;

class LabService
{
    use ImageHandlerTrait, LabSliderTrait, CleanupTrait;

    public function __construct(
        private LabRepository $repository,
        private LabTransformer $transformer,
        private FileService $fileService
    ) {
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }

    public function getDataTableData($request): array
    {
        $filters = [
            'name' => $request->input('name'),
            'status' => $request->input('status'),
            'facilities' => $request->input('facilities')
        ];

        $labs = $this->repository->getFilteredData($filters);

        $counter = 0;
        $transformedData = $labs->map(
            fn($lab) =>
            $this->transformer->transform($lab, ++$counter)
        );

        return [
            'data' => $transformedData,
            'meta' => [
                'total' => $labs->count(),
                'available' => $labs->where('status', 'tersedia')->count(),
                'unavailable' => $labs->where('status', 'tidak tersedia')->count(),
                'filtered' => $labs->count(),
            ],
        ];
    }

    public function findById(string $id): array
    {
        try {
            $lab = $this->repository->findById($id);
            return [
                'success' => true,
                'data' => $this->transformer->transform($lab)
            ];
        } catch (\Exception $e) {
            throw LabException::notFound($e->getMessage());
        }
    }

    public function store(LabData $data): array
    {
        try {
            DB::beginTransaction();

            \Log::info('Creating lab with data:', [
                'name' => $data->name,
                'facilities' => $data->facilities,
                'status' => $data->status,
                'user_id' => $data->user_id,
            ]);

            $payload = [
                'name' => $data->name,
                'facilities' => $data->facilities,
                'status' => $data->status,
                'user_id' => $data->user_id,
            ];

            // Handle thumbnail upload using updated trait method
            if ($data->thumbnail) {
                \Log::info('Handling thumbnail upload');
                $payload = $this->handleThumbnailUpload($data->thumbnail, $payload);
                \Log::info('Thumbnail payload:', $payload);
            }

            $lab = $this->repository->create($payload);
            \Log::info('Lab created with ID: ' . $lab->id);

            // Handle slider images
            if ($data->slider_images && !empty($data->slider_images)) {
                \Log::info('Handling slider images');
                $this->handleSliderImages($data->slider_images, $lab);
            }

            DB::commit();
            \Log::info('Lab creation committed to database');

            return [
                'success' => true,
                'message' => 'Lab berhasil ditambahkan',
                'data' => $this->transformer->transform($lab)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in LabService::store(): ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw LabException::failedToCreate($e->getMessage());
        }
    }

    public function update(Lab $lab, LabData $data): array
    {
        try {
            DB::beginTransaction();

            $payload = [
                'name' => $data->name,
                'facilities' => $data->facilities,
                'status' => $data->status,
            ];

            // Handle thumbnail update using updated trait method
            if ($data->thumbnail) {
                list($payload, $thumbnailUrl) = $this->updateThumbnail($data->thumbnail, $lab, $payload);
            }

            $this->repository->update($lab, $payload);

            // Handle slider images update
            if ($data->slider_images && !empty($data->slider_images)) {
                $newSliderImages = $this->updateSliderImages($data->slider_images, $lab);
            }

            $updatedLab = $this->repository->findById($lab->id);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Lab berhasil diperbarui',
                'data' => $this->transformer->transform($updatedLab)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw LabException::failedToUpdate($e->getMessage());
        }
    }

    public function delete(Lab $lab): array
    {
        try {
            DB::beginTransaction();

            $this->cleanupLabData($lab);
            $this->repository->delete($lab);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Lab berhasil dihapus'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw LabException::failedToDelete($e->getMessage());
        }
    }

    public function deleteAll(): array
    {
        try {
            DB::beginTransaction();

            $this->cleanupAllLabsData();
            $this->repository->deleteAll();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Semua lab berhasil dihapus'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw LabException::failedToDeleteAll($e->getMessage());
        }
    }
}