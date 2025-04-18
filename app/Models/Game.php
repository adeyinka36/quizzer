<?php

namespace App\Models;

use App\Http\Resources\PlayerResource;
use App\Http\Resources\TopicResource;
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
        'game_data',
    ];

    protected $casts = [
        'game_data' => 'array',
        'start_date_time' => 'datetime',
        'status' => 'string',
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

    public function storeGameJson(array $gameData): void
    {
        // Extract creator ID
        $creatorId = $gameData['creator_id'];

        // Fetch all players in a single query
        $players = Player::whereIn('id', $gameData['players'])->get();

        // Transform players using PlayerResource and add 'is_ready' field
        $gameData['players'] = $players->map(function ($player) use ($creatorId) {
            $playerData = (new PlayerResource($player))->toArray(request());
            $playerData['is_ready'] = $player->id === $creatorId;
            return $playerData;
        })->all();

        // Fetch and transform the topic
        $topic = Topic::find($gameData['topic_id']);
        $gameData['topic'] = $topic ? (new TopicResource($topic))->toArray(request()) : null;

        // Augment game data
        $gameData['id'] = $this->id;
        $gameData['name'] = $this->name;
        $gameData['status'] = $this->status;
        $gameData['creator'] = $creatorId;
        $gameData['winner_id'] = null;

        // Remove unnecessary keys
        unset($gameData['topic_id'], $gameData['creator_id']);

        // Assign and save game data
        $this->game_data = $gameData;
        $this->save();
    }
}
