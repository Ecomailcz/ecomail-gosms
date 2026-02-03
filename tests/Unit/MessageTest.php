<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit;

use EcomailGoSms\Message;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{

    public function testConstructor(): void
    {
        $message = new Message(message: 'Hello', channelId: 1, recipient: '+420733382412', customId: 'custom-123');

        self::assertSame('Hello', $message->message);
        self::assertSame(1, $message->channelId);
        self::assertSame('+420733382412', $message->recipient);
        self::assertSame('custom-123', $message->customId);
        self::assertNull($message->expectedSendStart);
    }

    public function testConstructorWithExpectedSendStart(): void
    {
        $message = new Message(message: 'Test', channelId: 2, recipient: '+420123456789', customId: 'id-1', expectedSendStart: '2025-01-15 10:00:00');

        self::assertSame('2025-01-15 10:00:00', $message->expectedSendStart);
    }

    public function testToArray(): void
    {
        $message = new Message('Hello', 1, '+420733382412', 'custom-123');
        $array = $message->toArray();

        self::assertSame(1, $array['channel']);
        self::assertSame('custom-123', $array['custom_id']);
        self::assertSame('Hello', $array['message']);
        self::assertSame('+420733382412', $array['recipient']);
        self::assertNull($array['expected_send_start']);
    }

    public function testToArrayWithExpectedSendStart(): void
    {
        $message = new Message('Test', 2, '+420111222333', 'id-1', '2025-01-15 10:00:00');
        $array = $message->toArray();

        self::assertSame('2025-01-15 10:00:00', $array['expected_send_start']);
    }

}
