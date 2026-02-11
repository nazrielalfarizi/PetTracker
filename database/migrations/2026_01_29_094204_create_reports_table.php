<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Identitas laporan
            $table->string('code')->unique();
            $table->foreignId('resident_id')->constrained()->cascadeOnDelete();

            // Jenis laporan
            $table->enum('type', ['kehilangan', 'temuan']);

            // Data umum
            $table->string('title');
            $table->longText('description');
            $table->string('image')->nullable();

            // Lokasi (langsung di tabel reports)
            $table->string('latitude');
            $table->string('longitude');
            $table->string('address');

            // Khusus laporan kehilangan
            $table->string('last_seen_location')->nullable();
            $table->text('pet_characteristics')->nullable();

            // Khusus laporan temuan
            $table->text('pet_condition')->nullable();

            // Status laporan
            $table->string('status', 20)->default('aktif');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
