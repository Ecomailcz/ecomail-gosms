<?php

declare(strict_types = 1);

$configDir = __DIR__ . '/../config';

if (!is_dir($configDir) && !mkdir($configDir, 0755, true) && !is_dir($configDir)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', $configDir));
}

$target = $configDir . '/gosms.php';
$fixture = __DIR__ . '/fixtures/gosms.config.php';

if (!is_file($target) && is_file($fixture)) {
    copy($fixture, $target);
}

require __DIR__ . '/../vendor/autoload.php';
