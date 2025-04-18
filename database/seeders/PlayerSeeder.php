<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Player::factory()
            ->count(1000)
            ->create();
        Player::factory()->create([
            'first_name' => 'Yolanda',
            'last_name' => 'Giwa',
            'username' => 'Yoly1992',
            'email' => 'yolanda@gmail.com',
            'password' => '$2y$12$5dD38u4zMCsfsV0fN0oc0e9hV7tlIhOjgQCP3/kBao4LPW4JACOq6',
            'avatar' => "https://images.unsplash.com/photo-1511367461989-f85a21fda167?q=80&w=1931&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
        ]);
        Player::factory()->create([
            'first_name' => 'Yinka',
            'last_name' => 'Giwa',
            'username' => 'Yinkax86',
            'email' => 'adeyinka.giwa36@gmail.com',
            'password' => '$2y$12$5dD38u4zMCsfsV0fN0oc0e9hV7tlIhOjgQCP3/kBao4LPW4JACOq6',
            'avatar' => "https://images.unsplash.com/photo-1511367461989-f85a21fda167?q=80&w=1931&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
        ]);
    }
}
