<?php

namespace App\Transformers;

class EquipmentTransformer
{
    public function transform($equipment): array
    {
        return [
            'id' => $equipment->id,
            'name' => $equipment->name,
            'code' => $equipment->code ?? '-',
            'stock' => [
                'value' => $equipment->stock,
                'formatted' => number_format($equipment->stock),
                'status' => $equipment->stock > 0 ? 'in-stock' : 'out-of-stock'
            ],
            'category' => [
                'id' => $equipment->category_id,
                'name' => $equipment->category ? $equipment->category->name : '-'
            ],
            'location' => [
                'id' => $equipment->location_id,
                'name' => $equipment->location ? $equipment->location->name : '-'
            ],
            'condition' => [
                'value' => $equipment->condition,
                'label' => ucfirst($equipment->condition),
                'status' => $this->getConditionStatus($equipment->condition)
            ],
            'image' => [
                'url' => $equipment->file ? $equipment->file->path_name : null,
                'thumbnail' => $equipment->file ? $equipment->file->thumbnail_path : null
            ],
            'description' => $equipment->description,
            'created_at' => $equipment->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $equipment->updated_at->format('Y-m-d H:i:s')
        ];
    }

    private function truncateDescription(?string $description): string
    {
        if (!$description)
            return '-';
        return strlen($description) > 50
            ? substr($description, 0, 50) . '...'
            : $description;
    }

    private function getConditionStatus(string $condition): string
    {
        return match ($condition) {
            'baik' => 'good',
            'rusak ringan' => 'minor-damage',
            'rusak berat' => 'major-damage',
            default => 'unknown'
        };
    }
}