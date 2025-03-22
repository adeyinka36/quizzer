<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->text('name')->nullable();
            $table->enum('status', ['IN_PROGRESS', 'COMPLETED', 'CANCELLED'])->default('IN_PROGRESS');
            $table->softDeletes();
            $table->timestamps();

            $table->foreignUuid('winner_id')->nullable()->constrained('players')->noActionOnDelete();
            $table->foreignUuid('creator_id')->constrained('players')->noActionOnDelete();
            $table->foreignUuid('topic_id')->references('id')->on('topics')->nullOnDelete();
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
