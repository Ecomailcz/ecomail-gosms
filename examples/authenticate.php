<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap.php';

$client = getAuthenticatedClient();

echo "Connection to API succeeded.\n";
echo 'Access token: ' . substr($client->getAccessToken() ?? '', 0, 20) . "...\n";
