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
    public function toExpoNotification(array $playerTokens, string $title, string $body, array $data): void
    {
        $payload = [
            'to' => $playerTokens,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ];
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://exp.host/--/api/v2/push/send', $payload);

        //resend if failed
        if ($response->failed()) {
            sleep(5);
            Log::error('Failed to send notification, retrying...');
             Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', $payload);
        }
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