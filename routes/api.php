<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('players')->group(function () {

    // Public routes
    Route::post('/register', [PlayerController::class, 'register'])->name('players.register'); // Register
    Route::post('/login', [PlayerController::class, 'login'])->name('players.login'); // Register

    Route::middleware(['auth:sanctum', 'ability:control-own-resources'])->group(function () {
        Route::put('/{player}', [PlayerController::class, 'update'])->name('players.update'); // Update player
        Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('players.destroy'); // Delete player
        Route::get('/{player}', [PlayerController::class, 'show'])->name('players.show');
    });

    Route::resource('/', PlayerController::class)->except([
        'put', 'destroy', 'delete', 'store'
    ]);
});


Route::prefix('questions')->group(function () {
    Route::middleware(['auth:sanctum', 'ability:can-view-questions'])->group(function () {
        Route::get('/',[QuestionController::class, 'index'])->name('questions');
    });
});
