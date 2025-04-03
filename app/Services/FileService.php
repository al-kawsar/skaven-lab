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

        return $this->createFileRecord($fileName, null, $size, $path . '/' . $fileName . '.' . $extension);
    }

    public function getFileById($id)
    {
        return FileModel::findOrFail($id);
    }

    public function deleteFileById($id)
    {
        $file = $this->getFileById($id);
        Storage::delete(str_replace('storage', 'public', $file->path_name));
        $file->delete();
        return $file;
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