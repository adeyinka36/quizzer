<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monetization extends Model
{
    /** @use HasFactory<\Database\Factories\MonetizationFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
