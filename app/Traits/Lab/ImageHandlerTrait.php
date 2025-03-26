<?php

namespace App\Traits\Lab;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Lab;

trait ImageHandlerTrait
{
    /**
     * Process base64 cropped image and save to storage
     */
    private function handleCroppedImage($croppedData, $directory)
    {
        // Decode the base64 image data
        list($type, $data) = explode(';', $croppedData);
        list(, $data) = explode(',', $data);
        $imageData = base64_decode($data);

        // Generate a unique filename
        $filename = uniqid() . '.jpg';

        // Save to storage
        $path = storage_path('app/public/' . $directory . '/' . $filename);
        file_put_contents($path, $imageData);

        // Create a file record
        $file = $this->fileService->createFileRecord(
            $filename,
            'image/jpeg',
            filesize($path),
            $directory . '/' . $filename
        );

        return $file;
    }

    /**
     * Handle thumbnail upload for new labs - overloaded to accept either Request or file data
     * @param Request|UploadedFile|string $input
     * @param array $payload
     * @return array
     */
    private function handleThumbnailUpload($input, array $payload): array
    {
        // Jika input adalah instance dari Request (untuk kompatibilitas dengan kode lama)
        if ($input instanceof Request) {
            if ($input->has('thumbnail_cropped_data') && !empty($input->thumbnail_cropped_data)) {
                $file = $this->handleCroppedImage($input->thumbnail_cropped_data, 'lab');
                $payload['thumbnail_id'] = $file->id;
            } elseif ($input->hasFile('thumbnail')) {
                $file = $this->fileService->uploadFile($input->file('thumbnail'), 'lab');
                $payload['thumbnail_id'] = $file->id;
            }
        } 
        // Jika input adalah file yang diupload
        elseif ($input instanceof UploadedFile) {
            $file = $this->fileService->uploadFile($input, 'lab');
            if ($file) {
                $payload['thumbnail_id'] = $file->id;
            }
        }
        // Jika input adalah base64 string
        elseif (is_string($input) && strpos($input, 'data:image') === 0) {
            $file = $this->handleCroppedImage($input, 'lab');
            if ($file) {
                $payload['thumbnail_id'] = $file->id;
            }
        }

        return $payload;
    }

    /**
     * Handle thumbnail update for existing labs - overloaded to accept either Request or file data
     * @param Request|UploadedFile|string $input
     * @param Lab $lab
     * @param array $payload
     * @return array
     */
    private function updateThumbnail($input, Lab $lab, array $payload): array
    {
        $thumbnailUrl = null;

        // Jika input adalah instance dari Request (untuk kompatibilitas dengan kode lama)
        if ($input instanceof Request) {
            if ($input->has('thumbnail_cropped_data') && !empty($input->thumbnail_cropped_data)) {
                // Delete old thumbnail if exists
                if ($lab->thumbnail_id) {
                    $this->fileService->deleteFileById($lab->thumbnail_id);
                }

                // Handle cropped thumbnail
                $file = $this->handleCroppedImage($input->thumbnail_cropped_data, 'lab');
                $payload['thumbnail_id'] = $file->id;
                $thumbnailUrl = $file->path_name;
            } elseif ($input->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($lab->thumbnail_id) {
                    $this->fileService->deleteFileById($lab->thumbnail_id);
                }

                // Upload new thumbnail
                $file = $this->fileService->uploadFile($input->file('thumbnail'), 'lab');
                $payload['thumbnail_id'] = $file->id;
                $thumbnailUrl = $file->path_name;
            }
        }
        // Jika input adalah file yang diupload
        elseif ($input instanceof UploadedFile) {
            // Delete old thumbnail if exists
            if ($lab->thumbnail_id) {
                $this->fileService->deleteFileById($lab->thumbnail_id);
            }

            // Upload new thumbnail
            $file = $this->fileService->uploadFile($input, 'lab');
            if ($file) {
                $payload['thumbnail_id'] = $file->id;
                $thumbnailUrl = $file->path_name;
            }
        }
        // Jika input adalah base64 string
        elseif (is_string($input) && strpos($input, 'data:image') === 0) {
            // Delete old thumbnail if exists
            if ($lab->thumbnail_id) {
                $this->fileService->deleteFileById($lab->thumbnail_id);
            }

            // Handle cropped thumbnail
            $file = $this->handleCroppedImage($input, 'lab');
            if ($file) {
                $payload['thumbnail_id'] = $file->id;
                $thumbnailUrl = $file->path_name;
            }
        } else {
            // Keep existing thumbnail
            $thumbnailUrl = $lab->thumbnail ? $lab->thumbnail->path_name : null;
        }

        return [$payload, $thumbnailUrl];
    }
}