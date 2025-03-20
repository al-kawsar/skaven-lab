<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lab_borrowing_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('borrowing_id')->constrained('lab_borrowings')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->comment('User yang melakukan perubahan');
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'digunakan',
                'selesai',
                'dibatalkan',
                'kadaluarsa'
            ]);
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable()->comment('Data tambahan seperti IP, browser, dll');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_histories');
    }
};
