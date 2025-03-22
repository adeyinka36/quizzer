<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Collection<int, Question> $questions
 * @property Collection<int, Player> $players
 */
class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'creator_id',
        'topic_id',
        'winner_id',
        'status',
    ];


    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id', 'id');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class);
    }


    public function creator()
    {
        return $this->belongsTo(Player::class, 'creator_id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function scopeWithQuestionsAndOptions($query)
    {
        return $query->with('questions.options');
    }


}
