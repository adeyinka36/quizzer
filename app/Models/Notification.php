<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    /** @use HasFactory<NotificationFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'player_id',
        'message',
        'type',
        'is_read',
        'title',
    ];
}
