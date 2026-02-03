<?php

declare(strict_types = 1);

use EcomailGoSms\Message;

require __DIR__ . '/bootstrap.php';

$client = getAuthenticatedClient();
$channelId = getChannelId();

$recipients = array_slice($argv, 1);

if ($recipients === []) {
    echo "Usage: php send-bulk-messages.php <phone1> [phone2] [phone3] ...\n";
    echo 'Example: php send-bulk-messages.php ' . EXAMPLES_ALLOWED_RECIPIENT . "\n";
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
        message: 'Bulk test SMS from examples.',
        channelId: $channelId,
        recipient: $recipient,
        customId: $batchId . '-' . ($index + 1),
    );
}

$response = $client->sendMessagesAsync($messages);

echo "Bulk send completed.\n";
echo 'Accepted: ' . $response->getTotalAccepted() . "\n";
echo 'Rejected: ' . $response->getTotalRejected() . "\n";

foreach ($response->getAccepted() as $sent) {
    echo '  [OK] ' . $sent->recipient . ' – ' . $sent->customId . "\n";
}

foreach ($response->getRejected() as $sent) {
    echo '  [REJECTED] ' . $sent->recipient . ' – ' . $sent->customId . "\n";
}
