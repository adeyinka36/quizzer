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
        Schema::create('monetization_player', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('monetization_id');
            $table->uuid('player_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('monetization_id')->references('id')->on('monetizations')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monetization_player');
    }
};
