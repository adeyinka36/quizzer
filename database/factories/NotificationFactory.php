<?php

namespace Database\Factories;

use App\enums\NotificationTitle;
use App\enums\NotificationType;
use App\Models\Notification;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $notificationTypesValues = array_column(NotificationType::cases(), 'value');
        $notificationTitleTypes = array_column(NotificationTitle::cases(), 'value');
        return [
            'type' => $this->faker->randomElement($notificationTypesValues),
            'message' => $this->faker->sentence,
            'is_read' => $this->faker->boolean,
            'title' => ucwords(strtolower(str_replace('_', ' ', $this->faker->randomElement($notificationTitleTypes)))),
            'player_id' => Player::inRandomOrder()->first()->id,
        ];
    }
}
