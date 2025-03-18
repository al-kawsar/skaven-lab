<?php

namespace App\DTOs\Lab;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * Data Transfer Object for Lab data
 * 
 * @property-read string|null $id
 * @property-read string $name
 * @property-read string $facilities
 * @property-read string $status
 * @property-read UploadedFile|string|null $thumbnail Thumbnail file (UploadedFile) or base64 encoded image
 * @property-read array|null $slider_images Array of slider images (UploadedFile or base64 encoded images)
 * @property-read string|null $user_id
 */
class LabData
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly string $facilities,
        public readonly string $status,
        public readonly ?string $thumbnail,
        public readonly ?array $slider_images,
        public readonly ?string $user_id
    ) {
    }

    /**
     * Create a LabData instance from a request
     * 
     * @param Request $request
     * @param string|null $id
     * @return self
     */
    public static function fromRequest(Request $request, ?string $id = null): self
    {
        // Tidak perlu mengubah tipe data auth()->id() karena UUID sudah string
        $userId = auth()->id();

        return new self(
            id: $id,
            name: $request->input('name'),
            facilities: $request->input('facilities', '-'),
            status: $request->input('status', 'tersedia'),
            thumbnail: $request->hasFile('thumbnail') ? $request->file('thumbnail') :
            ($request->has('thumbnail_cropped_data') ? $request->input('thumbnail_cropped_data') : null),
            slider_images: $request->hasFile('slider_images') ? $request->file('slider_images') : [],
            user_id: $userId
        );
    }
}