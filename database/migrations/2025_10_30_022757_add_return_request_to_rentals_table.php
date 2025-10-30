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
        Schema::table('rentals', function (Blueprint $table) {
            $table->timestamp('return_requested_at')->nullable()->after('notes');
            $table->boolean('return_request_status')->default(false)->after('return_requested_at')->comment('false = menunggu verifikasi, true = sudah diverifikasi admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['return_requested_at', 'return_request_status']);
        });
    }
};
