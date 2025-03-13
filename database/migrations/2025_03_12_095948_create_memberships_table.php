<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('membership_type', ['FREE', 'PREMIUM'])->default('FREE');

            $table->integer('price_cents')->default(0);

            $table->enum('duration', ['0', '1_MONTH', '3_MONTHS', '6_MONTHS', '1_YEAR'])->default('0');

            $table->timestampTz('start_date')->nullable();
            $table->timestampTz('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreignUuid('player_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
