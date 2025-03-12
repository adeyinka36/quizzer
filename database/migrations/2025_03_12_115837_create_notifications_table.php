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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type',['email', 'sms', 'push'])->default('push');
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->enum('title', [
                    'GIFT_CARD_AWARDED',
                    'GAME_INVITATION',
                    'ZIVAS_UPDATED',
                    'FRIEND_REQUEST',
                    'FRIEND_REQUEST_ACCEPTED',
                    'FRIEND_REQUEST_REJECTED',
                    'GAME_INVITATION_ACCEPTED',
                    'GAME_INVITATION_REJECTED',
                    'GAME_CANCELLED',
                    'GAME_ENDED',
                ]
            )->default('info');

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
        Schema::dropIfExists('notifications');
    }
};
