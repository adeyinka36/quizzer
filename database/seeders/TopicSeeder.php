<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\Question;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        Topic::factory()
            ->count(50)
            ->create()
            ->each(function ($topic) {
                Question::factory()->count(100)->create([
                    'topic_id' => $topic->id,
                ]);
            });
    }
}

