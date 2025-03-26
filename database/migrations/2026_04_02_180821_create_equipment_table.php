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
        Schema::create('equipment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->enum('condition', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');

            // Foreign key ke tabel kategori
            $table->foreignId('category_id')->nullable()->constrained('equipment_categories')->onDelete('set null');

            // Foreign key ke tabel lokasi
            $table->foreignId('location_id')->nullable()->constrained('equipment_locations')->onDelete('set null');

            // Foreign key ke tabel files (jika file menggunakan uuid)
            $table->unsignedBigInteger('file_id')->nullable();
            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};