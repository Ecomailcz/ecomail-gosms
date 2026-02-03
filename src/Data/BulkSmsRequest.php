<?php

declare(strict_types = 1);

namespace EcomailGoSms\Data;

use Spatie\LaravelData\Data;

final class BulkSmsRequest extends Data
{

    /**
     * @param array<int, string> $phoneNumbers
     */
    public function __construct(public readonly array $phoneNumbers, public readonly string $message, public readonly ?int $channel = null)
    {
    }

}
