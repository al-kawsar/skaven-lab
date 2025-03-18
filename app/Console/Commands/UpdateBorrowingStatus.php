<?php

namespace App\Console\Commands;

use App\Http\Controllers\Borrowing\LabBorrowingController;
use Illuminate\Console\Command;

class UpdateBorrowingStatus extends Command
{
    protected $signature = 'borrowing:update-status';
    protected $description = 'Update status peminjaman berdasarkan waktu';

    public function handle()
    {
        app(LabBorrowingController::class)->updateLabStatuses();
        $this->info('Status peminjaman berhasil diperbarui');
        return Command::SUCCESS;
    }
}
