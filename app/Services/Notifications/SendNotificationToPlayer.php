<?php

namespace App\Services\Notifications;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use YieldStudio\LaravelExpoNotifier\ExpoNotificationsChannel;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;
use Illuminate\Support\Facades\Http;

class SendNotificationToPlayer extends Notification
{
    public function via($notifiable): array
    {
        return [ExpoNotificationsChannel::class];
    }

    /**
     * @throws ConnectionException
     */
    public function toExpoNotification(array $playerTokens, string $title, string $body): ExpoMessage
    {
        Log::info('Sending notification to player tokens: ' . implode(', ', $playerTokens));

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://exp.host/--/api/v2/push/send', [
            'to' => $playerTokens,
            'title' => $title,
            'body' => $body,
        ]);
        Log::info('Sent direct Expo notification to---: ' );

        return (new ExpoMessage())
            ->to($playerTokens)
            ->title($title)
            ->body($body)
            ->channelId('default');
    }


    public static function sendDirectExpoNotification(string $token, string $title, string $body): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://exp.host/--/api/v2/push/send', [
            'to' => $token,
            'title' => $title,
            'body' => $body,
        ]);

        Log::info('Sent direct Expo notification to: ' . $token);
        Log::info('Expo response: ' . $response->body());

        return $response->json();
    }
}