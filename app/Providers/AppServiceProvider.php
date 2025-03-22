<?php

namespace App\Providers;

use App\Interfaces\UploadImageInterface;
use App\Models\Game;
use App\Models\Player;
use App\Observers\GameObserver;
use App\Observers\PlayerObserver;
use App\Services\UploadImageToS3;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->bind(UploadImageInterface::class, UploadImageToS3::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Game::observe(GameObserver::class);
        Player::observe(PlayerObserver::class);
    }
}
