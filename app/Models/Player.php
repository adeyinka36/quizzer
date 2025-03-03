<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Jobs\SendResetPasswordToken;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Random\RandomException;

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
        return $this->hasMany(Game::class);
    }

    public function monetization()
    {
        return $this->belongsToMany(Monetization::class);
    }

    public function getPermissions() {}

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
}
