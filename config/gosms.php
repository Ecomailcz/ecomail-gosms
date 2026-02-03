<?php

declare(strict_types = 1);

return [
    'client_id' => env('GOSMS_CLIENT_ID', ''),
    'client_secret' => env('GOSMS_CLIENT_SECRET', ''),
    'default_channel' => (int) env('GOSMS_DEFAULT_CHANNEL', 1),
];
