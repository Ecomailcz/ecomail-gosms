<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Exceptions;

use EcomailGoSms\Exceptions\InvalidNumber;
use PHPUnit\Framework\TestCase;

final class InvalidNumberTest extends TestCase
{

    public function testInvalidPhoneNumber(): void
    {
        $exception = InvalidNumber::invalidPhoneNumber('+420invalid');

        self::assertSame('Invalid phone number format: +420invalid', $exception->getMessage());
    }

}
