<?php

declare(strict_types = 1);

namespace EcomailGoSms\Data;

use Spatie\LaravelData\Data;

final class BulkSmsResponse extends Data
{

    /**
     * @param array<int, \EcomailGoSms\Data\SmsResponse> $results
     */
    public function __construct(
        public readonly array $results,
        public readonly int $totalCount,
        public readonly int $successCount,
        public readonly int $errorCount,
    ) {
    }

}
