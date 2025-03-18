<?php

namespace App\DTOs\Inventory;

class EquipmentData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $code,
        public readonly int $stock,
        public readonly string $condition,
        public readonly ?int $category_id,
        public readonly ?int $location_id,
        public readonly ?string $description,
        public readonly ?object $image
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            code: $request->code,
            stock: (int) $request->stock,
            condition: $request->condition,
            category_id: $request->category_id,
            location_id: $request->location_id,
            description: $request->description,
            image: $request->file('image')
        );
    }
}
