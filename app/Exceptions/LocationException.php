<?php

namespace App\Exceptions;

class LocationException extends \Exception
{
    public static function failedToCreate(string $message): self
    {
        return new self("Gagal membuat lokasi: {$message}");
    }

    public static function failedToUpdate(string $message): self
    {
        return new self("Gagal memperbarui lokasi: {$message}");
    }

    public static function failedToDelete(string $message): self
    {
        return new self("Gagal menghapus lokasi: {$message}");
    }
    
    public static function failedToBulkDelete(string $message): self
    {
        return new self("Gagal menghapus beberapa lokasi: {$message}");
    }
    
    public static function notFound(string $message = "Lokasi tidak ditemukan"): self
    {
        return new self($message);
    }
    
    public static function inUse(int $count): self
    {
        return new self("Lokasi tidak dapat dihapus karena sedang digunakan oleh {$count} barang.");
    }
    
    public static function invalidData(string $message): self
    {
        return new self("Data lokasi tidak valid: {$message}");
    }
}