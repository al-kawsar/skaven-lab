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
        Schema::create('lab_borrowings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('borrow_code')->nullable()->unique();
            $table->string('letter_code')->nullable()->unique();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('lab_id')->constrained('labs', 'id')->onDelete('cascade');
            $table->string('event');
            $table->enum('status', [
                'menunggu',      // Menunggu persetujuan admin
                'disetujui',     // Disetujui oleh admin
                'ditolak',       // Ditolak oleh admin
                'digunakan',     // Sedang digunakan
                'selesai',       // Peminjaman selesai
                'dibatalkan',    // Dibatalkan oleh peminjam
                'kadaluarsa'     // Melewati batas waktu peminjaman
            ])->default('menunggu');
            $table->date('borrow_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_borrowings');
    }
};
