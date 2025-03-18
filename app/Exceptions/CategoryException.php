<?php

namespace App\Exceptions;

class CategoryException extends \Exception
{
    public static function failedToCreate(string $message): self
    {
        return new self("Gagal membuat kategori: {$message}");
    }

    public static function failedToUpdate(string $message): self
    {
        return new self("Gagal memperbarui kategori: {$message}");
    }

    public static function failedToDelete(string $message): self
    {
        return new self("Gagal menghapus kategori: {$message}");
    }
}