<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Data;

use EcomailGoSms\Data\BulkSmsRequest;
use PHPUnit\Framework\TestCase;

final class BulkSmsRequestTest extends TestCase
{

    public function testConstructor(): void
    {
        $phoneNumbers = ['+420111111111', '+420222222222'];
        $request = new BulkSmsRequest($phoneNumbers, 'Bulk message', 1);

        self::assertSame($phoneNumbers, $request->phoneNumbers);
        self::assertSame('Bulk message', $request->message);
        self::assertSame(1, $request->channel);
    }

    public function testConstructorWithNullChannel(): void
    {
        $request = new BulkSmsRequest(['+420123456789'], 'Test');

        self::assertNull($request->channel);
    }

}
