<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Option;
use App\Models\Topic;
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
            'topic_id' => Topic::factory()->create()->id,
            'options' => [
                'A' => $this->faker->word,
                'B' => $this->faker->word,
                'C' => $this->faker->word,
                'D' => $this->faker->word,
            ],
            'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
        ];
    }

}
