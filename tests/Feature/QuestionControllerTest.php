<?php

use App\Models\Category;
use App\Models\Game;
use App\Models\Player;
use App\Models\Question;
use App\Models\Topic;
use Laravel\Sanctum\Sanctum;

it('Retrieves questions for a a given topic', function () {
    Sanctum::actingAs(
        $player = Player::factory()->create(),
        ['can-view-questions']
    );
    $topic = Topic::factory()->create();
     Question::factory()->count(10)->create([
        'topic_id' => $topic->id,
    ]);

    $response = $this->getJson('api/v1/questions?topic_id='.$topic->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                '*' => [
                    'id',
                    'question_text',
                    'options' => [
                            'A',
                            'B',
                            'C',
                            'D',
                    ],
                    'answer',
                    'topic_id'
                ],
            ],
        ]);
});
