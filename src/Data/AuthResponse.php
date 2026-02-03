<?php

declare(strict_types = 1);

namespace EcomailGoSms\Data;

use Spatie\LaravelData\Data;

final class AuthResponse extends Data
{

    public function __construct(public readonly string $accessToken, public readonly int $expiresIn, public readonly string $tokenType = 'Bearer')
    {
    }

}
