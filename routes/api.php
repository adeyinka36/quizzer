<?php

use App\enums\PlayerPermission;
use App\Http\Controllers\CustomTopicController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\StorePushTokenController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Players routes
    Route::prefix('players')->group(function () {
        // Public routes
        Route::post('/register', [PlayerController::class, 'register'])->name('players.register'); // Register
        Route::post('/login', [PlayerController::class, 'login'])->name('players.login'); // Login
        Route::post('request-password-reset-token', [PlayerController::class, 'requestPasswordResetToken'])->name('players.request-password-reset');
        Route::post('reset-password', [PlayerController::class, 'resetPassword'])->name('players.reset-password');

        Route::get('/{player}/stats', [StatsController::class, 'show'])->name('players.stats');

        Route::middleware(['auth:sanctum', 'ability:control-own-resources'])->group(function () {
            Route::post( 'image-upload/{player}', [PlayerController::class, 'imageUpload'])->name('players.image-upload');
            Route::put('/{player}', [PlayerController::class, 'update'])->name('players.update'); // Update player
            Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('players.destroy'); // Delete player
            Route::get('/{player}', [PlayerController::class, 'show'])->name('players.show');
            Route::get('/', [PlayerController::class, 'index'])->name('players.index');
        });

        Route::resource('/', PlayerController::class)->except([
            'put', 'destroy', 'delete', 'store',
        ]);
    });

    // Questions routes
    Route::prefix('questions')->group(function () {
        Route::middleware(['auth:sanctum', 'ability:can-view-questions'])->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('questions');
        });
    });

    // Games routes
    Route::prefix('games')->group(function () {
        Route::middleware(['can:view,game'])->group(function () {
            Route::get('/{game}', [GameController::class, 'show'])->name('games.show');
        });

        Route::middleware(['can:update,game'])->group(function () {
            Route::put('/{game}', [GameController::class, 'update'])->name('games.update');
        });

        Route::middleware(['auth:sanctum', PlayerPermission::CAN_CREATE_GAME->value])->group(function () {

        });
        Route::post('/', [GameController::class, 'store'])->name('games.store');

        Route::middleware(['can:destroy,game'])->group(function () {
            Route::delete('/{game}', [GameController::class, 'destroy'])->name('games.destroy');
        });

        Route::post('/game-invite-response/{game}/{player}', [GameController::class, 'acceptOrDeclineInvite'])->name('game-invite-response');

        Route::get('initiate-game/{game}', [GameController::class, 'initiateGame'])->name('initiate-game');
    });

    Route::get('/stats/{player}', [StatsController::class, 'show'])->name('stats.show');
    Route::get('/notifications/{player}', [NotificationController::class, 'show'])->name('notifications.index');
    Route::get('/topics/custom-topics', [CustomTopicController::class, 'index'])->name('custom-topics.index');
    Route::post('/topics/custom-topics', [CustomTopicController::class, 'store'])->name('custom-topics.store');
    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');

   Route::prefix('/friendships')->group(function () {
        Route::get('/{player}', [FriendshipController::class, 'show'])->name('friendships.index');
        Route::post('/', [FriendshipController::class, 'store'])->name('friendships.store');
        Route::put('/', [FriendshipController::class, 'update'])->name('friendships.update');
        Route::delete('/', [FriendshipController::class, 'destroy'])->name('friendships.destroy');
    });


   Route::post('/push-token', StorePushTokenController::class)->name('push-tokens.store');


});
