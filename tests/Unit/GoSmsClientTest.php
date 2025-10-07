<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit;

use EcomailGoSms\Exceptions\InvalidRequest;
use EcomailGoSms\GoSmsClient;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

use function fake;

final class GoSmsClientTest extends TestCase
{

    use GoSmsClientTestUtility;

    public function testGoSmsClientCanBeInstantiated(): void
    {
        $guzzleClient = Mockery::mock(GuzzleClient::class);
        $client = new GoSmsClient($guzzleClient, 'public-key', 'private-key');
        
        // Test that the client inherits the expected behavior
        self::assertNull($client->getAccessToken());
    }

    public function testGoSmsClientInheritsClientMethods(): void
    {
        $guzzleClient = Mockery::mock(GuzzleClient::class);
        $client = new GoSmsClient($guzzleClient, 'public-key', 'private-key', 'test-token');
        
        // Test that the client can access parent class methods
        self::assertSame('test-token', $client->getAccessToken());
    }

    public function testSendAsyncMessage(): void
    {
        $response = $this->createGoSmsClientWithJsonResponseAndAccessToken(__DIR__ . '/../Fixtures/send_message_async_success.json');
        $uuid = fake()->uuid();
        $phoneNumber = fake()->e164PhoneNumber();
        $channelId = fake()->randomDigit();
        $message = fake()->text();
        $response = $response->sendMessageAsync($message, $channelId, $phoneNumber, $uuid);

        self::assertSame('string', $response->getCustomId());
        self::assertSame('string', $response->getRecipient());
        self::assertSame('accepted', $response->getStatus());
        self::assertSame('string', $response->getLink());
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
        $client->sendMessageAsync($message, $channelId, $phoneNumber, $uuid);
    }

}
