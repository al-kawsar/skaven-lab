<?php

namespace App\Transformers;

use App\Models\EquipmentLocation;

class EquipmentLocationTransformer
{
    public function transform(EquipmentLocation $location, int $number = null): array
    {
        return [
            'id' => $location->id,
            'number' => $number,
            'name' => $location->name,
            'code' => $location->code ?? '-',
            'building' => $location->building ?? '-',
            'floor' => $location->floor ?? '-',
            'room' => $location->room ?? '-',
            'full_location' => $location->full_location,
            'description' => $this->truncateDescription($location->description),
            'equipment_count' => $location->equipment()->count(),
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