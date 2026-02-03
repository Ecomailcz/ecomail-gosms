<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Exceptions;

use EcomailGoSms\Exceptions\Authorization;
use PHPUnit\Framework\TestCase;

final class AuthorizationTest extends TestCase
{

    public function testInvalidCredentials(): void
    {
        $exception = Authorization::invalidCredentials();

        self::assertSame('Invalid GoSms credentials provided', $exception->getMessage());
    }

    public function testAuthenticationFailed(): void
    {
        $exception = Authorization::authenticationFailed();

        self::assertSame('GoSms authentication failed', $exception->getMessage());
    }

}
