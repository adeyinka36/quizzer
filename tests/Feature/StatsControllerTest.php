<?php

use App\Models\Game;
use App\Models\Player;

it('can get stats', function () {
    $player = Player::factory()->create();
    $morePlayers = Player::factory()->count(20)->create();

    $games = Game::factory()->count(10)->create([
        'creator_id' => $player->id,
    ]);

    $randomPlayers = $morePlayers->random(10);
    $games->each(function ($game, $index) use ($player, $randomPlayers) {
        $game->players()->attach($randomPlayers);
        $game->players()->attach($player);

        if ($index % 2 == 0) {
            $game->update(['winner_id' => $player->id]);
        }

        if ($index % 3 == 0) {
            $game->update(['winner_id' => $randomPlayers->random()->id]);
        }

        $game->update(['status' => 'COMPLETED']);
    });

    $response = $this->get('api/v1/players/'.$player->id.'/stats');


    $response->assertStatus(200)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                'games_played',
                'games_won',
                'games_lost',
                'win_rate',
                'games_drawn',
                'zivas',
                'zivas_redeemability'
            ],
        ]);
});

