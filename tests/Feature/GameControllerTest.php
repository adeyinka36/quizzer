<?php

use App\Events\GameCreated;
use App\Models\Game;
use App\Models\Player;

use App\Models\Topic;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

it('Can create a new game and dispatch GameCreated event', function () {
    // Fake events to intercept any event dispatching
    Event::fake([
        GameCreated::class,
    ]);

    $player = Player::factory()->create();
    Sanctum::actingAs(
        $player,
        ['control-own-resources', 'can-create-game']
    );

    $startDateTime = (new \DateTimeImmutable)->format('Y-m-d H:i:s');
    $topic = Topic::factory()->create();

    $data = [
        'start_date_time' => $startDateTime,
        'creator_id' => $player->id,
        'topic_id' => $topic->id,

    ];

    $response = $this->postJson('/api/v1/games', $data);

    Event::assertDispatched(GameCreated::class);

    $response->assertStatus(201)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                'id',
                'start_date_time',
                'name',
                'creator_id',
                'winner_id',
                'status',
                'topic_id',
            ],
        ]);
});

it('can update a game', function () {
    $player = Player::factory()->create();
    $game = Game::factory()->create(['creator_id' => $player->id]);
    Sanctum::actingAs(
        $player,
        ['control-own-resources', 'can-update-game', 'can-view-questions']
    );

    $newDateTime = (new \DateTimeImmutable)->modify('+1 day')->format('Y-m-d H:i:s');

    $data = [
        'name' => 'new name for game',
        'start_date_time' => $newDateTime,
    ];

    $response = $this->putJson('/api/v1/games/'.$game->id, $data);

    $response->assertStatus(200);
});

it('can show a game', function () {
    $player = Player::factory()->create();
    $game = Game::factory()->create(['creator_id' => $player->id]);

    $game->players()->attach($player);
    Sanctum::actingAs(
        $player,
        ['can-view-questions', 'can-view-game']
    );

    $response = $this->getJson('/api/v1/games/'.$game->id);

    $response->assertStatus(200);
});

it('can cancel a game', function () {
    $player = Player::factory()->create();
    $game = Game::factory()->create([
        'creator_id' => $player->id,
    ]);

    Sanctum::actingAs(
        $player,
        ['control-own-resources']
    );

    $response = $this->deleteJson('/api/v1/games/'.$game->id);

    $response->assertStatus(204);
});
