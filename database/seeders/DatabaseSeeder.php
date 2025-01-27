<?php

namespace Database\Seeders;

use App\Models\Player;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            PlayerSeeder::class,
            CategorySeeder::class,
            QuestionSeeder::class,
            GameSeeder::class,
            GamePlayerSeeder::class,
            GameQuestionSeeder::class,
       ]);
    }
}
