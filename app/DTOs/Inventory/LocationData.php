<?php

namespace App\DTOs\Inventory;

use Illuminate\Http\Request;

class LocationData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $code,
        public readonly ?string $description,
        public readonly ?string $building,
        public readonly ?string $floor,
        public readonly ?string $room,
    ) {
    }

    public static function fromRequest(Request $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            name: $request->input('name'),
            code: $request->input('code'),
            description: $request->input('description'),
            building: $request->input('building'),
            floor: $request->input('floor'),
            room: $request->input('room')
        );
    }
}