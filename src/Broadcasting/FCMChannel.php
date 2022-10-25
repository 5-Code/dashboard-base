<?php

namespace Habib\Dashboard\Broadcasting;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class FCMChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Authenticatable $notifiable
     * @param Notification $notification
     * @return array|null|void
     */
    public function send($notifiable, Notification $notification)
    {
        if (blank($to = $notifiable->routeNotificationFor('fcm', $notification))) {
            return;
        }

        $message = $notification->toFcm($notifiable);
        $options = method_exists($notification, 'options') ? $notification->options($notifiable) : [];
        $optionsNotification = method_exists($notification, 'optionsNotification') ?
            $notification->optionsNotification($notifiable) :
            [];
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('fcm.server_key');
        $ignoreKeys = config('fcm.ignore', []);

        $data = array_filter(array_merge([
            "registration_ids" => array_values(array_filter($to,
                fn($v) => !in_array($v, $ignoreKeys) || strlen($v) > 100)),
            "notification" => array_merge([
                "title" => $message['title'] ?? config('app.name'),
                "body" => $message['body'],
            ], $optionsNotification),
        ], $options));


        return Http::withHeaders(['Authorization' => "key={$serverKey}"])
            ->withoutVerifying()
            ->acceptJson()
            ->asJson()
            ->post($url, $data)
            ->json();
    }
}
