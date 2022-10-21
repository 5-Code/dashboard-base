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

        $to = $notifiable->routeNotificationFor('fcm', $notification);

        if (blank($to)) {
            return;
        }

        $message = $notification->toFcm($notifiable);
        $options = method_exists($notification, 'options') ? $notification->options($notifiable) : [];
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('fcm.server_key');

        $data = array_merge([
            "registration_ids" => array_values(array_filter($to, fn($v) => !in_array($v,
                    ['none', '_firebaseClient.notificationToken', '32123132132']) || strlen($v) > 100)),
            "notification" => [
                "title" => $message['title'] ?? config('app.name'),
                "body" => $message['body'],
            ]
        ], $options);


        return Http::withHeaders(['Authorization' => "key={$serverKey}"])->withoutVerifying()->acceptJson()->asJson()->post($url,
            $data)->json();
    }
}
