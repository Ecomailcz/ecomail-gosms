<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Exceptions;

use EcomailGoSms\Exceptions\InvalidFormat;
use PHPUnit\Framework\TestCase;

final class InvalidFormatTest extends TestCase
{

    public function testInvalidMessageFormat(): void
    {
        $exception = InvalidFormat::invalidMessageFormat('Too long');

        self::assertSame('Invalid message format: Too long', $exception->getMessage());
    }

    public function testInvalidPhoneNumber(): void
    {
        $exception = InvalidFormat::invalidPhoneNumber('Invalid format');

        self::assertSame('Invalid phone number: Invalid format', $exception->getMessage());
    }

}
