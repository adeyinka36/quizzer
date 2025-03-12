<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.-
     */
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('membership_type', ['FREE', 'STANDARD'])->default('FREE');
            $table->softDeletes();
            $table->timestamps();

            $table->foreignUuid('player_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
