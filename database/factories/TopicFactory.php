<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'image' => $this->faker->imageUrl(),
            'is_custom' => $this->faker->boolean,
            'creator_id' => Player::inRandomOrder()->first()->id,
            'is_active' => true,
        ];
    }
}
