<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Data;

use EcomailGoSms\Data\AuthResponse;
use PHPUnit\Framework\TestCase;

final class AuthResponseTest extends TestCase
{

    public function testConstructor(): void
    {
        $response = new AuthResponse('access-token', 3_600, 'Bearer');

        self::assertSame('access-token', $response->accessToken);
        self::assertSame(3_600, $response->expiresIn);
        self::assertSame('Bearer', $response->tokenType);
    }

    public function testConstructorDefaultTokenType(): void
    {
        $response = new AuthResponse('token', 1_800);

        self::assertSame('Bearer', $response->tokenType);
    }

}
