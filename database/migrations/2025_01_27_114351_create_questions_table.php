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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('question_text');
            $table->json('options');
            $table->enum('answer', ['A', 'B', 'C', 'D']);
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_active')->default(true);
            $table->dateTime('made_inactive_at')->nullable();




            $table->foreignUuid('topic_id')->references('id')->on('topics')->nullOnDelete();
            $table->foreignUuid('game_id')->nullable()->references('id')->on('games')->nullOnDelete();


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
