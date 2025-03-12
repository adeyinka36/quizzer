<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendshipsTable extends Migration
{
    public function up()
    {
        Schema::create('friendships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamps();

            $table->foreignUuid('requester_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreignUuid('addressee_id')->references('id')->on('players')->onDelete('cascade');

            $table->unique(['requester_id', 'addressee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('friendships');
    }
}
