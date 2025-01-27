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
        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['in_progress', 'completed', 'cancelled']);
            $table->uuid('monetization_id')->nullable();
            $table->uuid('winner_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('monetization_id')->references('id')->on('monetizations')->onDelete('cascade');
            $table->foreign('winner_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
