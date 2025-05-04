<?php

namespace App\DTOs\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class EquipmentData
{
    public function __construct(
      public readonly ?string $id,
      public readonly string $name,
      public readonly ?string $code,
      public readonly int $stock,
      public readonly string $condition,
      public readonly ?int $category_id,
      public readonly ?int $location_id,
      public readonly ?string $description,
      public readonly ?UploadedFile $image
  ) {}

    public static function fromRequest(Request $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            name: $request->input('name'),
            code: $request->input('code'),
            stock: (int) $request->input('stock'),
            condition: $request->input('condition'),
            category_id: $request->input('category_id'),
            location_id: $request->input('location_id'),
            description: $request->input('description'),
            image: $request->file('image')
        );
    }
}
