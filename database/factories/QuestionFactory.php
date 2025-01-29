<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_text' => $this->faker->sentence,
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function ($question) {
            // Generate 4 options for the question
            for ($i = 0; $i < 4; $i++) {
                Option::factory()->create([
                    'question_id' => $question->id,
                    'is_correct' => $i === 0, // First option is correct
                ]);
            }
        });

    }
}
