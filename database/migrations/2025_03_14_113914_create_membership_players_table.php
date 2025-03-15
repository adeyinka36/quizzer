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
        Schema::create('membership_player', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('membership_id')->references('id')->on('memberships')->cascadeOnDelete();
            $table->foreignUuid('player_id')->references('id')->on('players')->cascadeOnDelete();
            $table->dateTime('start_date')->default(now());
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_player');
    }
};
