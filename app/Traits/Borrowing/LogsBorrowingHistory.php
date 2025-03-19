<?php

namespace App\Traits\Borrowing;

use App\Models\LabBorrowingHistory;
use Illuminate\Http\Request;

trait LogsBorrowingHistory
{
    /**
     * Log borrowing history
     *
     * @param mixed $borrowing The borrowing model
     * @param string $status New status
     * @param string $notes Notes about the status change
     * @param Request|null $request Current request if available
     * @return void
     */
    protected function logBorrowingHistory($borrowing, $status, $notes = null, Request $request = null)
    {
        $metadata = [];

        if ($request) {
            $metadata = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toIso8601String()
            ];
        }

        // Override notes if not provided
        if (empty($notes)) {
            $statusTexts = [
                'menunggu' => 'Menunggu persetujuan',
                'disetujui' => 'Disetujui oleh ' . (auth()->user() ? auth()->user()->name : 'Sistem'),
                'ditolak' => 'Ditolak',
                'dibatalkan' => 'Dibatalkan oleh ' . (auth()->user() ? auth()->user()->name : 'Sistem'),
                'digunakan' => 'Sedang digunakan',
                'selesai' => 'Peminjaman selesai',
                'kadaluarsa' => 'Peminjaman kadaluarsa (otomatis)'
            ];

            $notes = $statusTexts[$status] ?? 'Status diubah menjadi ' . $status;
        }

        return $borrowing->histories()->create([
            'user_id' => auth()->id(), // Can be null if system
            'status' => $status,
            'notes' => $notes,
            'metadata' => $metadata
        ]);
    }
}
