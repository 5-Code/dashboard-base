<?php

return [
    'server_key' => env('FCM_SERVER_KEY'),
    'sender_id' => env('FCM_SENDER_ID'),
    'icon' => env('FCM_ICON'),
    'icon_color' => env('FCM_ICON_COLOR'),
    'sound' => env('FCM_SOUND'),
    'vibrate' => env('FCM_VIBRATE'),
    'priority' => env('FCM_PRIORITY'),
    'ignore' => [
        'none',
        '_firebaseClient.notificationToken',
        '32123132132',
    ],
];
