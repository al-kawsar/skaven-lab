<?php

namespace App\Transformers;

use App\Models\EquipmentCategory;

class EquipmentCategoryTransformer
{
    public function transform(EquipmentCategory $category, int $number = null): array
    {
        return [
            'id' => $category->id,
            'number' => $number,
            'name' => $category->name,
            'code' => $category->code ?? '-',
            'description' => $this->truncateDescription($category->description),
            'color' => $category->color,
            'equipment_count' => $category->equipment()->count(),
        ];
    }

    private function truncateDescription(?string $description): string
    {
        if (!$description) {
            return '-';
        }

        return strlen($description) > 50
            ? substr($description, 0, 50) . '...'
            : $description;
    }
}