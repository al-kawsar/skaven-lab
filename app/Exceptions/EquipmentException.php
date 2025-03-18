<?php

namespace App\Exceptions;

use \Exception;

class EquipmentException extends Exception
{
    public static function failedToCreate(string $message): self
    {
        return new self("Gagal menambahkan data barang: {$message}");
    }

    public static function failedToUpdate(string $message): self
    {
        return new self("Gagal memperbarui data barang: {$message}");
    }

    public static function failedToDelete(string $message): self
    {
        return new self("Gagal menghapus data barang: {$message}");
    }

    public static function notFound(int $id): self
    {
        return new self("Data barang dengan ID {$id} tidak ditemukan");
    }
}