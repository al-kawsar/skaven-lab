<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Convert a base64 encoded image string to a file and save it
     * 
     * @param string $base64Image
     * @param string $directory
     *
     */
    public static function saveBase64Image($base64Image, $directory)
    {
        // Check if the input is a valid base64 image
        if (Str::startsWith($base64Image, 'data:image')) {
            // Extract image type and data
            list($type, $data) = explode(';', $base64Image);
            list(, $data) = explode(',', $data);

            // Get the image extension
            $extension = self::getImageExtension($type);

            // Generate unique filename
            $filename = Str::uuid() . '.' . $extension;

            // Decode base64 data
            $imageData = base64_decode($data);

            // Save the file to storage
            Storage::disk('public')->put($directory . '/' . $filename, $imageData);

            return [
                'filename' => $filename,
                'mime_type' => str_replace('data:', '', $type),
                'size' => strlen($imageData),
                'path' => $directory . '/' . $filename,
                'full_path' => Storage::disk('public')->url($directory . '/' . $filename)
            ];
        }

        return null;
    }

    /**
     * Get the image extension from the mime type
     * 
     * @param string $mimeType
     * @return string
     */
    private static function getImageExtension($mimeType)
    {
        switch ($mimeType) {
            case 'data:image/jpeg':
            case 'data:image/jpg':
                return 'jpg';
            case 'data:image/png':
                return 'png';
            case 'data:image/gif':
                return 'gif';
            case 'data:image/webp':
                return 'webp';
            default:
                return 'jpg';
        }
    }
}