<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use App\Models\Player;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(Player $player, Request $request )
    {
                $notifications = $player->notifications()->paginate(10);
                $data = [
                    "_links" => [
                        "_self" => $notifications->url($notifications->currentPage()),
                        "next" => $notifications->hasMorePages() ? $notifications->nextPageUrl() : null,
                        "previous" => $notifications->onFirstPage() ? null : $notifications->previousPageUrl(),
                    ],
                    "count" => $notifications->count(),
                    "total" => $notifications->total(),
                    "data" => $notifications->items()
                ];

                return response()->json(($data), 200);
    }
}
