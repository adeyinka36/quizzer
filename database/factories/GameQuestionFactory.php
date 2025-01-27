<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameQuestion>
 */
class GameQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_id' => Game::inRandomOrder()->first()->id,
            'question_id' => Question::inRandomOrder()->first()->id,
        ];
    }
}
