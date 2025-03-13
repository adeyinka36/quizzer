<?php

namespace App\Models;

use Database\Factories\TopicFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    /** @use HasFactory<TopicFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }


    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
