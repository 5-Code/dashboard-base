<?php

namespace Habib\Dashboard\Broadcasting;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SocketChannel
{
    /**
     * @param  Authenticatable  $notifiable
     * @param  Notification  $notification
     * @return array|mixed|void
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable?->routeNotificationFor('socket', $notification);

        if (blank($to)) {
            return;
        }

        $message = $notification->toSocket($notifiable);
        $message['channels'][] = $to;
        $message['channels'] = array_unique($message['channels']);
        if (! $url = method_exists($notification, 'socketUrl') ? $notification->socketUrl($notifiable,
            $notification) : config('dashboard.socket_url')) {
            return;
        }
        $response = Http::acceptJson()->withoutVerifying()->asJson()->post($url, $message);
        logger()->info("SocketChannel: {$url} => {$response->getStatusCode()}", $response->json());

        return $response;
    }
}
