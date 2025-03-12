<?php

namespace App\Models;

use Database\Factories\FriendshipFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friendship extends Model
{
    /** @use HasFactory<FriendshipFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'requester_id',
        'addressee_id',
        'status'
    ];

    public function requester()
    {
        return $this->belongsTo( Player::class, 'requester_id');
    }

    public function addressee()
    {
        return $this->belongsTo(Player::class, 'addressee_id');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function friendshipsSent(): HasMany
    {
        return $this->hasMany(Friendship::class, 'requester_id')->where('status', 'accepted');
    }

    public function friendshipsReceived(): HasMany
    {
        return $this->hasMany(Friendship::class, 'addressee_id')->where('status', 'accepted');
    }

    public function friends()
    {
        $sentFriendIds = $this->friendshipsSent->pluck('addressee_id')->toArray();
        $receivedFriendIds = $this->friendshipsReceived->pluck('requester_id')->toArray();
        $friendIds = array_unique(array_merge($sentFriendIds, $receivedFriendIds));

        return Player::whereIn('id', $friendIds)->get();
    }

}
