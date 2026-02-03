<?php

declare(strict_types = 1);

namespace EcomailGoSms\Data;

use Spatie\LaravelData\Data;

final class SmsRequest extends Data
{

    public function __construct(public readonly string $phoneNumber, public readonly string $message, public readonly ?int $channel = null)
    {
    }

}
