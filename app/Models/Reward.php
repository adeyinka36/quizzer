<?php

namespace App\Models;

use Database\Factories\RewardFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    /** @use HasFactory<RewardFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'player_id',
        'amount',
        'form',
    ];


    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
