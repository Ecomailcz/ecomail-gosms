<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Data;

use EcomailGoSms\Data\SmsRequest;
use PHPUnit\Framework\TestCase;

final class SmsRequestTest extends TestCase
{

    public function testConstructor(): void
    {
        $request = new SmsRequest('+420733382412', 'Hello', 1);

        self::assertSame('+420733382412', $request->phoneNumber);
        self::assertSame('Hello', $request->message);
        self::assertSame(1, $request->channel);
    }

    public function testConstructorWithNullChannel(): void
    {
        $request = new SmsRequest('+420123456789', 'Test');

        self::assertNull($request->channel);
    }

}
