<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Data;

use EcomailGoSms\Data\SmsResponse;
use PHPUnit\Framework\TestCase;

final class SmsResponseTest extends TestCase
{

    public function testConstructor(): void
    {
        $response = new SmsResponse(123, 'sent');

        self::assertSame(123, $response->id);
        self::assertSame('sent', $response->status);
        self::assertNull($response->errorMessage);
    }

    public function testConstructorWithErrorMessage(): void
    {
        $response = new SmsResponse(456, 'failed', 'Invalid number');

        self::assertSame('Invalid number', $response->errorMessage);
    }

}
