<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public function monetization()
    {
        return $this->belongsTo(Monetization::class);
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id', 'id');
    }

    public function player()
    {
        return $this->belongsToMany(Player::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}
