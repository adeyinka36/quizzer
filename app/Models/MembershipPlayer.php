<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlayer extends Model
{
    protected $table = 'membership_player';
    protected $fillable = [
        'membership_id',
        'player_id',
        'start_date',
    ];
}
