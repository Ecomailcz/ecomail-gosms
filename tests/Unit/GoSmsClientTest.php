<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit;

use EcomailGoSms\Exceptions\BadRequest;
use EcomailGoSms\Exceptions\InvalidRequest;
use EcomailGoSms\Message;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function fake;
use function file_get_contents;

final class GoSmsClientTest extends TestCase
{

    use GoSmsClientTestUtility;

    public function testSendAsyncMessage(): void
    {
        $response = $this->createGoSmsClientWithJsonResponseAndAccessToken(__DIR__ . '/../Fixtures/send_message_async_success.json');
        $uuid = fake()->uuid();
        $phoneNumber = fake()->e164PhoneNumber();
        $channelId = fake()->randomDigit();
        $message = fake()->text();
        $dto = new Message($message, $channelId, $phoneNumber, $uuid);
        $response = $response->sendMessageAsync($dto);

        self::assertSame('695395e54046d', $response->getCustomId());
        self::assertSame('+420733382412', $response->getRecipient());
        self::assertSame('accepted', $response->getStatus());
        self::assertSame('/api/v2/messages/by-custom-id/695395e54046d', $response->getLink());
    }

    public function testSendAsyncMessages(): void
    {
        $response = $this->createGoSmsClientWithJsonResponseAndAccessToken(__DIR__ . '/../Fixtures/send_messages_async.json');
        $uuid = fake()->uuid();
        $phoneNumber = fake()->e164PhoneNumber();
        $channelId = fake()->randomDigit();
        $message = fake()->text();
        $response = $response->sendMessagesAsync(
            [new Message($message, $channelId, $phoneNumber, $uuid), new Message($message, $channelId, $phoneNumber, $uuid)],
        );

        self::assertSame(2, $response->getTotalAccepted());
        self::assertSame(0, $response->getTotalRejected());
    }

    public function testMessageDetail(): void
    {
        $response = $this->createGoSmsClientWithJsonResponseAndAccessToken(__DIR__ . '/../Fixtures/message_detail.json');
        $uuid = '6953a029b6061';
        $response = $response->getMessageStatistics($uuid);

        self::assertSame($uuid, $response->getCustomId());
        self::assertSame(1, $response->getTotalCount());
        self::assertCount(1, $response->getMessages());
        self::assertArrayHasKey('status', $response->getMessages()[0]);
        self::assertArrayHasKey('recipient', $response->getMessages()[0]);
        self::assertArrayHasKey('custom_id', $response->getMessages()[0]);
        self::assertArrayHasKey('created_at', $response->getMessages()[0]);
        self::assertArrayHasKey('updated_at', $response->getMessages()[0]);
        self::assertArrayHasKey('error', $response->getMessages()[0]);
    }

    public function testSendAsyncMessageWithExpectedSendStart(): void
    {
        $response = $this->createGoSmsClientWithJsonResponseAndAccessToken(__DIR__ . '/../Fixtures/send_message_async_success.json');
        $uuid = fake()->uuid();
        $phoneNumber = fake()->e164PhoneNumber();
        $channelId = fake()->randomDigit();
        $message = fake()->text();
        $expectedSendStart = fake()->dateTime()->format('Y-m-d H:i:s');
        $dto = new Message($message, $channelId, $phoneNumber, $uuid, $expectedSendStart);
        $response = $response->sendMessageAsync($dto);

        self::assertSame('695395e54046d', $response->getCustomId());
        self::assertSame('+420733382412', $response->getRecipient());
        self::assertSame('accepted', $response->getStatus());
        self::assertSame('/api/v2/messages/by-custom-id/695395e54046d', $response->getLink());
    }

    public function testSendAsyncMessageWithError(): void
    {

        $this->expectException(InvalidRequest::class);

        $response = new Response(422, [], 'Error');
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/messages/');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);
        $uuid = fake()->uuid();
        $phoneNumber = fake()->e164PhoneNumber();
        $channelId = fake()->randomDigit();
        $message = fake()->text();
        $dto = new Message($message, $channelId, $phoneNumber, $uuid);
        $client->sendMessageAsync($dto);
    }

    public function testSendAsyncMessageWithInvalidChannel(): void
    {
        $this->expectException(BadRequest::class);

        $body = (string) file_get_contents(__DIR__ . '/../Fixtures/send_message_async_invalid_channel.json');
        $response = new Response(403, [], $body);
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/messages/');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);
        $uuid = fake()->uuid();
        $phoneNumber = fake()->e164PhoneNumber();
        $channelId = fake()->randomDigit();
        $message = fake()->text();
        $dto = new Message($message, $channelId, $phoneNumber, $uuid);
        $client->sendMessageAsync($dto);
    }

}
