<?php

namespace App\DTOs\Inventory;

use Illuminate\Http\Request;

class CategoryData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $code,
        public readonly ?string $description,
        public readonly ?string $color,
    ) {
    }

    public static function fromRequest(Request $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            name: $request->input('name'),
            code: $request->input('code'),
            description: $request->input('description'),
            color: $request->input('color')
        );
    }
}