<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\File as FileModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function uploadFile($file, $path = false)
    {
        $fileName = Str::random(24);
        $extension = $file->getClientOriginalExtension();
        $path = $path ?? date('Y') . '/' . date('m') . '/' . date('d');
        $size = round($file->getSize() / 1024);
        $pathName = '/storage/' . $path . '/' . $fileName . '.' . $extension;

        Storage::put('/public/' . $path . '/' . $fileName . '.' . $extension, File::get($file));

        $data = FileModel::create([
            'url' => request()->schemeAndHttpHost(),
            'path_name' => $pathName,
            'file_name' => $fileName,
            'extension' => $extension,
            'size' => $size
        ]);

        return $data;
    }

    public function getFileById($id)
    {
        $file = FileModel::findOrFail($id);

        return $file;
    }

    public function deleteFileById($id)
    {
        $file = FileModel::findOrFail($id);

        Storage::delete(str_replace('storage', 'public', $file->path_name));

        $file->delete();

        return $file;
    }

    public function createFileFromBase64($base64Image, $directory)
    {
        // Use the ImageHelper to save the file
        $fileData = \App\Helpers\ImageHelper::saveBase64Image($base64Image, $directory);

        if ($fileData) {
            // Create a file record
            return $this->createFileRecord(
                $fileData['filename'],
                $fileData['mime_type'],
                $fileData['size'],
                $fileData['path']
            );
        }

        return null;
    }

    public function createFileRecord($fileName, $mimeType, $size, $pathName)
    {
        $data = FileModel::create([
            'url' => request()->schemeAndHttpHost(),
            'path_name' => '/storage/' . $pathName,
            'file_name' => $fileName,
            'extension' => pathinfo($fileName, PATHINFO_EXTENSION),
            'size' => $size
        ]);

        return $data;
    }
}