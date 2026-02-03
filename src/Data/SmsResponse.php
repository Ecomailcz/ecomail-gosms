<?php

declare(strict_types = 1);

namespace EcomailGoSms\Data;

use Spatie\LaravelData\Data;

final class SmsResponse extends Data
{

    public function __construct(public readonly int $id, public readonly string $status, public readonly ?string $errorMessage = null)
    {
    }

}
