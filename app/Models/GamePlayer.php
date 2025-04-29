<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GamePlayer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'game_player';

    protected $fillable = ['game_id', 'player_id'];
}
