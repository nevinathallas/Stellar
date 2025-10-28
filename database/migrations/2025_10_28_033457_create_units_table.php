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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // kode unit wajib unik
            $table->string('name'); // nama boleh sama
            $table->decimal('price_per_day', 10, 2)->default(0); // harga sewa per hari
            $table->enum('status', ['available', 'rented'])->default('available');
            $table->timestamps();
            
            $table->index('name'); // index untuk pencarian nama unit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
