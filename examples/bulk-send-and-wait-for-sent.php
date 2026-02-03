<?php

declare(strict_types = 1);

use EcomailGoSms\GoSmsClient;
use EcomailGoSms\Message;
use GuzzleHttp\Client as GuzzleClient;

require __DIR__ . '/bootstrap.php';

const TOKEN_CACHE_FILE = __DIR__ . '/.gosms-token-cache.json';

/**
 * @return array{access_token: string, refresh_token: string}|null
 */
function loadTokenFromCache(): ?array
{
    if (!is_readable(TOKEN_CACHE_FILE)) {
        return null;
    }

    $content = file_get_contents(TOKEN_CACHE_FILE);

    if ($content === false) {
        return null;
    }

    $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

    if (!is_array($data) || !isset($data['access_token']) || !is_string($data['access_token']) || $data['access_token'] === '') {
        return null;
    }

    $refresh = $data['refresh_token'] ?? '';

    if (!is_string($refresh)) {
        $refresh = '';
    }

    return ['access_token' => $data['access_token'], 'refresh_token' => $refresh];
}

/**
 * @param array{access_token: string, refresh_token: string} $token
 */
function saveTokenToCache(array $token): void
{
    file_put_contents(
        TOKEN_CACHE_FILE,
        json_encode($token, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
        LOCK_EX,
    );
}

function getClientWithCachedToken(): GoSmsClient
{
    $clientId = is_string($_ENV['GOSMS_CLIENT_ID'] ?? null) ? $_ENV['GOSMS_CLIENT_ID'] : '';
    $clientSecret = is_string($_ENV['GOSMS_CLIENT_SECRET'] ?? null) ? $_ENV['GOSMS_CLIENT_SECRET'] : '';

    if ($clientId === '' || $clientSecret === '') {
        throw new RuntimeException('Set GOSMS_CLIENT_ID and GOSMS_CLIENT_SECRET in examples/.env (copy from .env.example)');
    }

    $app = getApplication();
    $channelId = getChannelId();
    $httpClient = $app->make(GuzzleClient::class);

    $cached = loadTokenFromCache();

    if ($cached !== null) {
        return new GoSmsClient($clientId, $clientSecret, $cached['access_token'], $channelId, 'password', '', $httpClient);
    }

    $baseClient = new GoSmsClient($clientId, $clientSecret, null, $channelId, 'password', '', $httpClient);

    $auth = $baseClient->authenticate();
    saveTokenToCache([
        'access_token' => $auth->getAccessToken(),
        'refresh_token' => $auth->getRefreshToken(),
    ]);

    return new GoSmsClient(
        $clientId,
        $clientSecret,
        $auth->getAccessToken(),
        $channelId,
        'password',
        '',
        $httpClient,
    );
}

$client = getClientWithCachedToken();
$channelId = getChannelId();

$recipients = array_slice($argv, 1);

if ($recipients === []) {
    echo "Usage: php bulk-send-and-wait-for-sent.php <phone1> [phone2] ...\n";
    echo 'Example: php bulk-send-and-wait-for-sent.php ' . EXAMPLES_ALLOWED_RECIPIENT . "\n";
    echo 'Examples may only send to ' . EXAMPLES_ALLOWED_RECIPIENT . ".\n";
    exit(1);
}

foreach ($recipients as $recipient) {
    ensureAllowedRecipient($recipient);
}

$batchId = 'batch-' . uniqid('', true);
$messages = [];

foreach ($recipients as $index => $recipient) {
    $messages[] = new Message(
        message: 'Bulk test SMS – waiting for sent status.',
        channelId: $channelId,
        recipient: $recipient,
        customId: $batchId . '-' . ($index + 1),
    );
}

$response = $client->sendMessagesAsync($messages);

echo "Bulk send completed.\n";
echo 'Accepted: ' . $response->getTotalAccepted() . "\n";
echo 'Rejected: ' . $response->getTotalRejected() . "\n\n";

$pollIntervalSeconds = 3;
$maxAttempts = 60;

foreach ($response->getAccepted() as $sent) {
    echo 'Waiting for "sent" status for custom_id: ' . $sent->customId . ' …' . "\n";

    $attempt = 0;

    while ($attempt < $maxAttempts) {
        $statusResponse = $client->getMessageStatistics($sent->customId);
        $statusMessages = $statusResponse->getMessages();

        if ($statusMessages === []) {
            echo "  No messages in response.\n";

            break;
        }

        $first = $statusMessages[0];
        $status = is_string($first['status'] ?? null) ? $first['status'] : '';

        echo '  [' . ($attempt + 1) . '] status: ' . $status . "\n";

        if ($status === 'sent') {
            echo "\nMessage completed (sent). Response data:\n";
            echo "--- MessageStatusResponse ---\n";
            echo 'custom_id: ' . $statusResponse->getCustomId() . "\n";
            echo 'total_count: ' . $statusResponse->getTotalCount() . "\n";
            echo "\n--- messages array (each item = one message) ---\n";

            foreach ($statusResponse->getMessages() as $i => $msg) {
                echo 'messages[' . $i . "]:\n";

                foreach ($msg as $key => $value) {
                    $display = match (true) {
                        $value === null => 'null',
                        is_scalar($value) => (string) $value,
                        default => json_encode($value, JSON_UNESCAPED_UNICODE),
                    };
                    echo '  ' . $key . ': ' . $display . "\n";
                }
            }

            echo "---\n";

            break;
        }

        $attempt++;

        if ($attempt < $maxAttempts) {
            sleep($pollIntervalSeconds);
        } else {
            echo "  Max attempts reached, stopping.\n";
        }
    }
}
