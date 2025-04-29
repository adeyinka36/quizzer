<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Membership;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MembershipSeeder::class,
            PlayerSeeder::class,
            TopicSeeder::class,
            GameSeeder::class,
            GamePlayerSeeder::class,
            NotificationSeeder::class
        ]);
    }
}
