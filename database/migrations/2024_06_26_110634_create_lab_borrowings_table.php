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
            $table->uuid('id')->primary(); // ID unik sebagai primary key
            $table->string('borrow_code')->nullable()->unique(); // Kode peminjaman yang unik
            $table->string('letter_code')->nullable()->unique(); // Kode surat peminjaman yang unik
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade'); // ID pengguna yang meminjam, terhubung ke tabel users
            $table->string('event'); // Nama acara atau kegiatan
            $table->enum('status', [
                'menunggu',      // Menunggu persetujuan admin
                'disetujui',     // Disetujui oleh admin
                'ditolak',       // Ditolak oleh admin
                'digunakan',     // Sedang digunakan
                'selesai',       // Peminjaman selesai
                'dibatalkan',    // Dibatalkan oleh peminjam
                'kadaluarsa'     // Melewati batas waktu peminjaman
            ])->default('menunggu'); // Status peminjaman dengan nilai default 'menunggu'
            $table->boolean('is_recurring')->default(false); // Apakah peminjaman berulang atau tidak
            $table->string('recurrence_type')->nullable(); // Tipe pengulangan (harian, mingguan, bulanan)
            $table->integer('recurrence_interval')->nullable(); // Interval pengulangan (setiap berapa hari/minggu/bulan)
            $table->date('recurrence_ends_at')->nullable(); // Tanggal berakhirnya pengulangan
            $table->integer('recurrence_count')->nullable(); // Jumlah pengulangan
            $table->uuid('parent_booking_id')->nullable(); // ID peminjaman induk untuk peminjaman berulang
            $table->foreign('parent_booking_id')->references('id')->on('lab_borrowings')->onDelete('cascade'); // Relasi ke peminjaman induk
            $table->date('borrow_date'); // Tanggal peminjaman
            $table->time('start_time'); // Waktu mulai peminjaman
            $table->time('end_time'); // Waktu selesai peminjaman
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps(); // Waktu pembuatan dan pembaruan record
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
