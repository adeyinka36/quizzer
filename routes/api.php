<?php

use App\enums\PlayerPermission;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Players routes
    Route::prefix('players')->group(function () {
        // Public routes
        Route::post('/register', [PlayerController::class, 'register'])->name('players.register'); // Register
        Route::post('/login', [PlayerController::class, 'login'])->name('players.login'); // Login
        Route::post('request-password-reset-token', [PlayerController::class, 'requestPasswordResetToken'])->name('players.request-password-reset');
        Route::post('reset-password', [PlayerController::class, 'resetPassword'])->name('players.reset-password');

        Route::middleware(['auth:sanctum', 'ability:control-own-resources'])->group(function () {
            Route::put('/{player}', [PlayerController::class, 'update'])->name('players.update'); // Update player
            Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('players.destroy'); // Delete player
            Route::get('/{player}', [PlayerController::class, 'show'])->name('players.show');
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
            Route::post('/', [GameController::class, 'store'])->name('games.store');
        });

        Route::middleware(['can:destroy,game'])->group(function () {
            Route::delete('/{game}', [GameController::class, 'destroy'])->name('games.destroy');
        });
    });
});
