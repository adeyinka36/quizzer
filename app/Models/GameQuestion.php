<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameQuestion extends Pivot
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['game_id', 'question_id'];
}
