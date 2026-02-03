<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Data;

use EcomailGoSms\Data\AuthRequest;
use PHPUnit\Framework\TestCase;

final class AuthRequestTest extends TestCase
{

    public function testConstructor(): void
    {
        $request = new AuthRequest('client-id', 'client-secret');

        self::assertSame('client-id', $request->clientId);
        self::assertSame('client-secret', $request->clientSecret);
    }

}
