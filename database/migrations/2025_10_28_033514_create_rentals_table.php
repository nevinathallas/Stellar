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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // peminjam
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // unit disewa
            $table->date('start_date');
            $table->date('end_date'); // maksimal 5 hari (validasi di aplikasi)
            $table->timestamp('returned_at')->nullable(); // diisi saat admin mengembalikan
            $table->integer('duration_days'); // Harus <= 5 (divalidasi di aplikasi)
            $table->decimal('fine', 10, 2)->default(0.00); // denda jika telat
            $table->enum('status', ['ongoing', 'returned', 'overdue'])->default('ongoing');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['unit_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
