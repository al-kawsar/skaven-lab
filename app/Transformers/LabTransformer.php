<?php

namespace App\Transformers;

use App\Models\Lab;

class LabTransformer
{
    public function transform(Lab $lab, int $number = null): array
    {
        return [
            'id' => $lab->id,
            'number' => $number,
            'name' => $lab->name,
            'status' => $lab->status,
            'facilities' => $this->truncateFacilities($lab->facilities),
            'thumbnail_url' => $lab->file ? $lab->file->path_name : null,
            'slider_images' => $lab->sliderImages ? $lab->sliderImages->map(
                fn($slider) =>
                ['id' => $slider->id, 'url' => $slider->file->path_name]
            ) : [],
        ];
    }

    private function truncateFacilities(string $facilities): string
    {
        return strlen($facilities) > 80
            ? substr($facilities, 0, 80) . '...'
            : $facilities;
    }
}