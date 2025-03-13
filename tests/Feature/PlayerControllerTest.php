<?php

use App\Models\Player;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

it('stores a new player successfully', function () {
    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'username' => 'johndoe',
        'password' => 'Secret2000###',
        'confirm_password' => 'Secret2000###',
    ];
    $response = $this->postJson('/api/v1/players/register', $data);

    $response->assertStatus(201)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'username',
                'zivas',
                'is_subscribed',
            ],
            'token',
        ]);

    $this->assertDatabaseHas('players', [
        'email' => $data['email'],
        'username' => $data['username'],
    ]);

    $player = Player::where('email', $data['email'])->first();
    expect(Hash::check('Secret2000###', $player->password))->toBeTrue();
});

it('retrieves a player successfully', function () {
    $player = Player::factory()->create();
    Sanctum::actingAs(
        Player::factory()->create(),
        ['control-own-resources']
    );

    $response = $this->getJson("/api/v1/players/{$player->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'username',
                'zivas',
                'is_subscribed',
            ],
            'token',
        ])
        ->assertJson([
            '_links' => ['self' => ['href' => "api/v1/players/{$player->id}"]],
            'data' => [
                'id' => $player->id,
                'first_name' => $player->first_name,
                'last_name' => $player->last_name,
                'email' => $player->email,
                'username' => $player->username,
                'zivas' => $player->zivas,
                'is_subscribed' => $player->is_subscribed,
            ],
        ]);
});

it('logs in and retrieves a player', function () {
    $player = Player::factory()->create(['password' => 'Secret222###']);
    $data = [
        'email' => $player->email,
        'password' => 'Secret222###',
    ];

    $response = $this->postJson('/api/v1/players/login', $data);

    $response->assertStatus(200)
        ->assertJsonStructure([
            '_links' => ['self' => ['href']],
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'username',
                'zivas',
                'is_subscribed',
            ],
            'token',
        ])
        ->assertJson([
            '_links' => ['self' => ['href' => "api/v1/players/{$player->id}"]],
            'data' => [
                'id' => $player->id,
                'first_name' => $player->first_name,
                'last_name' => $player->last_name,
                'email' => $player->email,
                'username' => $player->username,
                'zivas' => $player->zivas,
                'is_subscribed' => $player->is_subscribed,
            ],
        ]);
});

it('passes validation when the data is valid', function () {
    // Create a player to update
    $player = Player::factory()->create();
    Sanctum::actingAs(
        Player::factory()->create(),
        ['control-own-resources']
    );

    // Mock valid update data
    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $player->email, // Same email as the current record
        'username' => $player->username, // Same username as the current record
        'password' => 'Newpassword123###',
        'current_password' => 'Secret2000###', // Assuming the Player factory uses 'password' by default
        'password_confirmation' => 'Newpassword123###',
    ];

    // Perform the request
    $response = $this->putJson("api/v1/players/{$player->id}", $data);

    // Assert that the response is successful
    $response->assertStatus(200);
});

it('fails validation if the email is already taken by another player', function () {
    // Create two players
    $player1 = Player::factory()->create(['email' => 'john.doe@example.com']);
    $player2 = Player::factory()->create(['email' => 'jane.doe@example.com']);
    Sanctum::actingAs(
        Player::factory()->create(),
        ['control-own-resources']
    );

    // Mock invalid update data (email already taken by player1)
    $data = [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => $player1->email, // This email belongs to player1
        'username' => $player2->username,
        'current_password' => 'password',
    ];

    // Perform the request
    $response = $this->putJson("api/v1/players/{$player2->id}", $data);

    // Assert validation failure
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('fails validation if the current password is incorrect', function () {
    // Create a player
    $player = Player::factory()->create();
    Sanctum::actingAs(
        Player::factory()->create(),
        ['*']
    );

    // Mock invalid update data (incorrect current password)
    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $player->email,
        'username' => $player->username,
        'password' => 'Newpassword123###',
        'current_password' => 'Wrongpassword23###', // Incorrect password
        'password_confirmation' => 'Newpassword123###',
    ];

    // Perform the request
    $response = $this->putJson("api/v1/players/{$player->id}", $data);

    // Assert validation failure
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['current_password']);
});

it('fails validation if the password confirmation does not match', function () {
    // Create a player
    $player = Player::factory()->create();
    Sanctum::actingAs(
        Player::factory()->create(),
        ['*']
    );

    // Mock invalid update data (password confirmation mismatch)
    $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $player->email,
        'username' => $player->username,
        'password' => 'Newpassword123###',
        'current_password' => 'Secret2000###',
        'password_confirmation' => 'Newpassword12444###', // Does not match password
    ];

    $response = $this->putJson("api/v1/players/{$player->id}", $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password_confirmation']);
});

it('allows an authenticated user to delete a player', function () {
    $player = Player::factory()->create();
    Sanctum::actingAs(
        Player::factory()->create(),
        ['*']
    );

    $response = $this->deleteJson("api/v1/players/{$player->id}");

    $response->assertStatus(204);

    $this->assertDatabaseHas('players', [
        'id' => $player->id,
        'deleted_at' => now(),
    ]);
});

it('prevents an unauthenticated user from deleting a player', function () {
    $player = Player::factory()->create();

    $response = $this->deleteJson("api/v1/players/{$player->id}");

    $response->assertStatus(401);

    $this->assertDatabaseHas('players', ['id' => $player->id]);
});

it('returns 404 if the player does not exist', function () {
    Sanctum::actingAs(
        Player::factory()->create(),
        ['*']
    );

    $response = $this->deleteJson('api/v1/players/no-existent-id');

    $response->assertStatus(404);
});

it('sends a password reset email when valid data is provided', function () {
    // Create a player with known details.
    $player = Player::factory()->create([
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'email' => 'alice@example.com',
    ]);

    $data = [
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'email' => 'alice@example.com',
    ];

    $response = $this->postJson('/api/v1/players/request-password-reset-token', $data);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Password reset email sent',
        ]);
});

it('returns error when an invalid email is provided for token request', function () {
    $data = [
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'email' => 'nonexistent@example.com',
    ];

    $response = $this->postJson('/api/v1/players/request-password-reset-token', $data);

    $response->assertStatus(404)
        ->assertJson([
            'error' => 'Invalid email provided',
        ]);
});

it('returns error when provided names do not match for token request', function () {
    // Create a player with known details.
    $player = Player::factory()->create([
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'email' => 'alice@example.com',
    ]);

    $data = [
        'first_name' => 'Alicia', // Mismatch here
        'last_name' => 'Smith',
        'email' => 'alice@example.com',
    ];

    $response = $this->postJson('/api/v1/players/request-password-reset-token', $data);

    $response->assertStatus(401)
        ->assertJson([
            'error' => 'Invalid names provided',
        ]);
});

// Tests for resetPassword

it('returns error if passwords do not match during reset', function () {
    // Create a player with a valid token.
    $player = Player::factory()->create([
        'password_reset_token' => 'valid-token',
    ]);

    $data = [
        'token' => 'valid-token',
        'password' => 'NewPassword123',
        'confirm_password' => 'MismatchPassword123',
    ];

    $response = $this->postJson('/api/v1/players/reset-password', $data);

    $response->assertStatus(404)
        ->assertJson([
            'error' => 'Passwords do not match',
        ]);
});

it('updates the password successfully when valid token and matching passwords are provided', function () {
    $token = 'valid-token';
    $password = 'NewPassword123';
    $player = Player::factory()->create([
        'password_reset_token' => $token,
    ]);

    $data = [
        'token' => $token,
        'password' => 'NewPassword123',
        'confirm_password' => 'NewPassword123',
    ];

    $response = $this->postJson('/api/v1/players/reset-password', $data);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Password updated',
        ]);

    $player = Player::find($player->id);
    expect(Hash::check($password, $player->password))->toBeTrue();
});

it('returns error when token is invalid during password reset', function () {
    $token = 'invalid-token';
    // Create a player with a different token.
    $player = Player::factory()->create([
        'password_reset_token' => 'valid-token',
    ]);

    $data = [
        'token' => $token,
        'password' => 'NewPassword123',
        'confirm_password' => 'NewPassword123',
    ];

    $response = $this->postJson('/api/v1/players/reset-password', $data);

    $response->assertStatus(404)
        ->assertJson([
            'error' => 'Invalid token',
        ]);
});
