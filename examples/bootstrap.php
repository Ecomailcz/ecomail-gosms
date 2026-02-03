<?php

declare(strict_types = 1);

use EcomailGoSms\GoSmsClient;
use Illuminate\Foundation\Application;

require __DIR__ . '/../vendor/autoload.php';

$envFiles = [
    __DIR__ . '/.env',
    __DIR__ . '/../.env',
];

foreach ($envFiles as $envFile) {
    if (!is_readable($envFile)) {
        continue;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines === false) {
        continue;
    }

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0b\"'");
    }

    break;
}

require __DIR__ . '/ApplicationFactory.php';

function getApplication(): Application
{
    /** @var \Illuminate\Foundation\Application|null $app */
    static $app = null;
    $app ??= new ApplicationFactory('createApp')->createApp();

    return $app;
}

function getAuthenticatedClient(): GoSmsClient
{
    $clientId = $_ENV['GOSMS_CLIENT_ID'] ?? '';
    $clientSecret = $_ENV['GOSMS_CLIENT_SECRET'] ?? '';

    if ($clientId === '' || $clientSecret === '') {
        throw new RuntimeException('Set GOSMS_CLIENT_ID and GOSMS_CLIENT_SECRET in examples/.env (copy from .env.example)');
    }

    $client = getApplication()->make('gosms.authenticated');

    assert($client instanceof GoSmsClient);

    return $client;
}

define('EXAMPLES_ALLOWED_RECIPIENT', '+420733382412');

function ensureAllowedRecipient(string $recipient): void
{
    if ($recipient !== EXAMPLES_ALLOWED_RECIPIENT) {
        echo 'Examples may only send to ' . EXAMPLES_ALLOWED_RECIPIENT . ".\n";
        exit(1);
    }
}

function getChannelId(): int
{
    $channelIdRaw = $_ENV['GOSMS_CHANNEL_ID'] ?? null;

    if (!is_scalar($channelIdRaw)) {
        throw new RuntimeException('Set GOSMS_CHANNEL_ID (channel number) in examples/.env');
    }

    $channelIdStr = (string) $channelIdRaw;

    if ($channelIdStr === '' || !ctype_digit($channelIdStr)) {
        throw new RuntimeException('Set GOSMS_CHANNEL_ID (channel number) in examples/.env');
    }

    return (int) $channelIdStr;
}
