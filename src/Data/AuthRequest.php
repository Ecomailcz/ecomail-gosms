<?php

declare(strict_types = 1);

namespace EcomailGoSms\Data;

use Spatie\LaravelData\Data;

final class AuthRequest extends Data
{

    public function __construct(public readonly string $clientId, public readonly string $clientSecret)
    {
    }

}
