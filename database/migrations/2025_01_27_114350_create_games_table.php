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
            $table->text('name')->unique()->nullable();
            $table->enum('status', ['IN_PROGRESS', 'COMPLETED', 'CANCELLED', 'HOLD'])->default('HOLD');
            $table->dateTime('start_date_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->timestamps();

            $table->foreignUuid('winner_id')->nullable()->constrained('players')->noActionOnDelete();
            $table->foreignUuid('creator_id')->nullable()->constrained('players')->noActionOnDelete();
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
