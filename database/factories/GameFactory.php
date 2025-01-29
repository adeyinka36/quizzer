<?php

namespace Database\Factories;

use App\Models\Game;
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
        $statuses = ['completed', 'in_progress', 'cancelled', 'hold'];

        return [
            'status' => $statuses[random_int(0, 3)],
        ];
    }

    //       create 10 questions for each game
    public function configure(): static
    {
        return $this->afterCreating(function (Game $game) {
            $questions = Question::factory()->count(10)->create();
            foreach ($questions as $question) {
                $game->questions()->attach($question->id, [
                    'id' => \Illuminate\Support\Str::uuid(),
                ]);
            }
        });
    }
}
