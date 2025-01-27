<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameQuestion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'game_question';

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
