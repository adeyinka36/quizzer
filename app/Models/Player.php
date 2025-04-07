<?php

namespace App\Models;

use App\Jobs\SendResetPasswordToken;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Random\RandomException;;

class Player extends Authenticatable
{
    /** @use HasFactory<PlayerFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
        'password_reset_token',
        'password_reset_token_created_at',
        'is_member', //todo replace with membership_id or attribute in model
        'avatar',
        'push_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'id' => 'string',
        ];
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class);
    }

    public function notifications(): hasMany
    {
        return $this->hasMany(Notification::class);
    }


    public function sentPlayerRequests()
    {
        return $this->belongsToMany(
            Player::class,   // The related model
            'friendships',   // Pivot table
            'requester_id',  // Foreign key on pivot for THIS model
            'addressee_id'   // Foreign key on pivot for the RELATED model
        )->wherePivot('status', 'sent');
    }

    /**
     * Players who have sent friend requests TO this player.
     * That is, this user is the 'addressee' and the other is 'requester',
     * with pivot status='sent'.
     */
    public function receivedPlayerRequests()
    {
        return $this->belongsToMany(
            Player::class,
            'friendships',
            'addressee_id',
            'requester_id'
        )->wherePivot('status', 'sent');
    }

    public function friends()
    {
        // Subquery A: current player is the requester
        $subA = Player::select('players.*')
            ->join('friendships', 'friendships.addressee_id', '=', 'players.id')
            ->where('friendships.requester_id', $this->id)
            ->where('friendships.status', 'accepted');

        // Subquery B: current player is the addressee
        $subB = Player::select('players.*')
            ->join('friendships', 'friendships.requester_id', '=', 'players.id')
            ->where('friendships.addressee_id', $this->id)
            ->where('friendships.status', 'accepted');

        // Union both subqueries
        return Player::query()
            ->fromSub($subA->union($subB), 'players')
            ->select('players.*');
    }

    public function isFriendWith(string|int $playerId): bool
    {
        if (! $playerId) {
            return false;
        }

        return Friendship::where([
            'requester_id' => $this->id,
            'addressee_id' => $playerId
        ])
            ->orWhere([
                'requester_id' => $playerId,
                'addressee_id' => $this->id
            ])
            ->exists();
    }


    /**
     * @throws \Exception
     */
    public function updatePassword(string $password, string $token): bool
    {
        if ($this->password_reset_token !== $token) {
            return false;
        }
        $this->update([
            'password' => $password,
        ]);

        return true;
    }

    /**
     * @throws RandomException
     */
    public function sendPasswordResetNotification($token): void
    {
        // generate a string of 16 characters
        $token = bin2hex(random_bytes(16));
        $this->update([
            'password_reset_token' => $token,
            'password_reset_token_created_at' => now(),
        ]);
        SendResetPasswordToken::dispatch($this, $token);
    }

    public function getStats(): array
    {
        $games_played = $this->games()->count();
        $games_won = $this->games()->where('winner_id', $this->id)->count();
        $games_drawn = $this->games()->where([
            'winner_id' => null,
            'status' => 'COMPLETED',
        ])->count();
        $games_lost = $games_played - ($games_won + $games_drawn);
        $win_rate = $games_played > 0 ? ($games_won / $games_played) * 100 : 0;
        $zivas = $this->zivas;

        $zivasCanBeRedeemedOn = $this->calculateZivaRedeemDate();


        return [
            'games_played' => $games_played,
            'games_won' => $games_won,
            'games_lost' => $games_lost,
            'win_rate' => $win_rate,
            'games_drawn' => $games_drawn,
            'zivas' => $zivas,
            'zivas_redeemable_date' => $zivasCanBeRedeemedOn,
        ];
    }


    public function calculateZivaRedeemDate()
    {
        $memberShipStartDate = $this->membership?->start_date;

        //zivas can  be redeeemed only when the membership has been active for 30 or more days.
        //if the membership has been active for less than 30 days, the zivas can be redeemed after 30 days of the membership start date.
        //zivas need to be 1000 to be redeemed for a £10 gift card.
        if($this->zivas < 1000 || !$memberShipStartDate){
            return null;
        }

        return $memberShipStartDate->addDays(30)->format('Y-m-d');
    }

    public function getIsFriendAttribute()
    {
        return $this->isFriendWith(auth()?->user());
    }

    public function sendGameInvite(Game $game)
    {
        $notifier  = app('expo_push_notification');

        $playerTokens = $game->players()
            ->whereNotNull('push_token')
            ->pluck('push_token')
            ->toArray();

        $notifier->toExpoNotification(
            playerTokens: $playerTokens,
            title: 'Game Invite',
            body: 'You have been invited to join a game.'
        );
    }
}
