<?php

namespace App\Models;

use App\Models\Scopes\ActiveQuestionScope;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([ActiveQuestionScope::class])]
class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $casts = [
        'options' => 'array',
    ];

    protected $fillable = [
        'question',
        'answer',
        'options',
        'game_id',
        'creator_id',
        'topic_id',
    ];


    public function topics()
    {
        return $this->belongsToMany(Topic::class)
            ->using(Topic::class)
            ->withTimestamps();
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }


}
