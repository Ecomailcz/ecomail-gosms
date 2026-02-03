<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Requests;

use EcomailGoSms\Message;
use EcomailGoSms\Requests\SendMessageAsyncRequest;
use PHPUnit\Framework\TestCase;

final class SendMessageAsyncRequestTest extends TestCase
{

    public function testGetMethod(): void
    {
        $message = new Message('Hello', 1, '+420733382412', 'custom-id');
        $request = new SendMessageAsyncRequest($message);

        self::assertSame('POST', $request->getMethod());
    }

    public function testGetEndpoint(): void
    {
        $message = new Message('Hello', 1, '+420733382412', 'custom-id');
        $request = new SendMessageAsyncRequest($message);

        self::assertSame('https://api.gosms.eu/api/v2/messages/', $request->getEndpoint());
    }

    public function testGetOptions(): void
    {
        $message = new Message('Test message', 2, '+420123456789', 'uuid-123');
        $request = new SendMessageAsyncRequest($message);
        $options = $request->getOptions();

        self::assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        self::assertSame(2, $json['channel']);
        self::assertSame('uuid-123', $json['custom_id']);
        self::assertSame('Test message', $json['message']);
        self::assertSame('+420123456789', $json['recipient']);
        self::assertArrayNotHasKey('expected_send_start', $json);
    }

    public function testGetOptionsWithExpectedSendStart(): void
    {
        $message = new Message('Test', 1, '+420111222333', 'id-1', '2025-01-15 10:00:00');
        $request = new SendMessageAsyncRequest($message);
        $options = $request->getOptions();

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        self::assertSame('2025-01-15 10:00:00', $json['expected_send_start']);
    }

}
