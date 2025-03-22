<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Player;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws RandomException
     */
    public function definition(): array
    {
        $statuses = ['COMPLETED', 'IN_PROGRESS', 'CANCELLED'];

        return [
            'status' => $statuses[random_int(0, 2)],
            'topic_id' => Question::factory()->create()->topic_id,
            'creator_id' => Player::inRandomOrder()->first()->id,
        ];
    }
}
