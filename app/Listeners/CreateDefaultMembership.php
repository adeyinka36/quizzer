<?php

namespace App\Listeners;

use App\enums\MembershipType;
use App\Events\PlayerCreated;
use App\Models\Membership;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateDefaultMembership
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PlayerCreated $event): void
    {
       $player = $event->player;
       $membership = Membership::where('type', MembershipType::FREE->value)->first();
       $player->memberships()->attach($membership->id);
       $player->update([
           'is_member' => true
       ]);
    }
}
