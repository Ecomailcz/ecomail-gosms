<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Requests;

use EcomailGoSms\Message;
use EcomailGoSms\Requests\SendMessagesAsyncRequest;
use PHPUnit\Framework\TestCase;

final class SendMessagesAsyncRequestTest extends TestCase
{

    public function testGetMethod(): void
    {
        $messages = [new Message('Hello', 1, '+420733382412', 'id-1')];
        $request = new SendMessagesAsyncRequest($messages);

        self::assertSame('POST', $request->getMethod());
    }

    public function testGetEndpoint(): void
    {
        $messages = [new Message('Hello', 1, '+420733382412', 'id-1')];
        $request = new SendMessagesAsyncRequest($messages);

        self::assertSame('https://api.gosms.eu/api/v2/messages/bulk', $request->getEndpoint());
    }

    public function testGetOptions(): void
    {
        $messages = [
            new Message('First', 1, '+420111111111', 'id-1'),
            new Message('Second', 2, '+420222222222', 'id-2'),
        ];
        $request = new SendMessagesAsyncRequest($messages);
        $options = $request->getOptions();

        self::assertArrayHasKey('json', $options);

        /** @var array<string, mixed> $json */
        $json = $options['json'];
        self::assertArrayHasKey('messages', $json);

        /** @var array<int, array<string, mixed>> $messagesArray */
        $messagesArray = $json['messages'];
        self::assertCount(2, $messagesArray);
        self::assertSame(1, $messagesArray[0]['channel']);
        self::assertSame('id-1', $messagesArray[0]['custom_id']);
        self::assertSame('First', $messagesArray[0]['message']);
        self::assertSame('+420111111111', $messagesArray[0]['recipient']);
        self::assertSame(2, $messagesArray[1]['channel']);
        self::assertSame('id-2', $messagesArray[1]['custom_id']);
    }

}
