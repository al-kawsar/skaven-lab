<?php

namespace App\Exceptions;

class LabException extends \Exception
{
    public static function failedToCreate(string $message): self
    {
        return new self("Gagal membuat lab: {$message}");
    }

    public static function failedToUpdate(string $message): self
    {
        return new self("Gagal memperbarui lab: {$message}");
    }

    public static function failedToDelete(string $message): self
    {
        return new self("Gagal menghapus lab: {$message}");
    }

    public static function failedToDeleteAll(string $message): self
    {
        return new self("Gagal menghapus semua lab: {$message}");
    }

    public static function notFound(string $message = "Lab tidak ditemukan"): self
    {
        return new self($message);
    }

    public static function invalidData(string $message): self
    {
        return new self("Data lab tidak valid: {$message}");
    }
}
