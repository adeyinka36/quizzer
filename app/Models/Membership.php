<?php

namespace App\Models;

use Database\Factories\MembershipFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    /** @use HasFactory<MembershipFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'player_id',
        'type',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
