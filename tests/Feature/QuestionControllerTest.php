<?php

use App\Models\Category;
use App\Models\Game;
use App\Models\Player;
use Laravel\Sanctum\Sanctum;

it('Retrieves questions for a a given game', function () {
    Category::factory()->create();
    $game = Game::factory()->create(['status' => 'IN_PROGRESS']);

    Sanctum::actingAs(
        Player::factory()->create(),
        ['can-view-questions']
    );

    $response = $this->getJson('api/v1/questions?game_id='.$game->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                '*' => [
                    'question',
                    'options' => [
                        '*' => [
                            'option_text',
                            'is_correct',
                        ],
                    ],
                ],
            ],
        ]);
});
