<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Requests;

use EcomailGoSms\Requests\MessageStatusRequest;
use PHPUnit\Framework\TestCase;

final class MessageStatusRequestTest extends TestCase
{

    public function testGetMethod(): void
    {
        $request = new MessageStatusRequest('custom-123');

        self::assertSame('GET', $request->getMethod());
    }

    public function testGetEndpoint(): void
    {
        $request = new MessageStatusRequest('6953a029b6061');

        self::assertSame('https://api.gosms.eu/api/v2/messages/by-custom-id/6953a029b6061', $request->getEndpoint());
    }

    public function testGetOptionsReturnsEmptyArray(): void
    {
        $request = new MessageStatusRequest('custom-123');
        $options = $request->getOptions();

        self::assertSame([], $options);
    }

}
