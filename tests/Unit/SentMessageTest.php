<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit;

use EcomailGoSms\SentMessage;
use PHPUnit\Framework\TestCase;

final class SentMessageTest extends TestCase
{

    public function testConstructor(): void
    {
        $sentMessage = new SentMessage(
            status: 'accepted',
            recipient: '+420733382412',
            customId: '6953ab3ad4eb3',
            link: '/api/v2/messages/by-custom-id/6953ab3ad4eb3',
        );

        self::assertSame('accepted', $sentMessage->status);
        self::assertSame('+420733382412', $sentMessage->recipient);
        self::assertSame('6953ab3ad4eb3', $sentMessage->customId);
        self::assertSame('/api/v2/messages/by-custom-id/6953ab3ad4eb3', $sentMessage->link);
    }

    public function testFromArray(): void
    {
        $data = [
            'custom_id' => '6953ab3ad4eb3',
            'link' => '/api/v2/messages/by-custom-id/6953ab3ad4eb3',
            'recipient' => '+420733382412',
            'status' => 'accepted',
        ];

        $sentMessage = SentMessage::fromArray($data);

        self::assertSame('accepted', $sentMessage->status);
        self::assertSame('+420733382412', $sentMessage->recipient);
        self::assertSame('6953ab3ad4eb3', $sentMessage->customId);
        self::assertSame('/api/v2/messages/by-custom-id/6953ab3ad4eb3', $sentMessage->link);
    }

}
