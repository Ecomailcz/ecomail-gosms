<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Http;

use EcomailGoSms\Exceptions\Request as GoSmsRequestException;
use EcomailGoSms\Http\GuzzleHttpClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class GuzzleHttpClientTest extends TestCase
{

    public function testRequestReturnsBodyAndStatus(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"access_token":"token","refresh_token":"refresh","token_type":"Bearer"}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $client = new GuzzleHttpClient($guzzleClient);

        $result = $client->request('POST', 'auth/token', ['client_id' => 'id', 'client_secret' => 'secret']);

        self::assertSame(200, $result['status']);
        self::assertSame('token', $result['body']['access_token']);
        self::assertSame('refresh', $result['body']['refresh_token']);
        self::assertSame('Bearer', $result['body']['token_type']);
    }

    public function testRequestThrowsOnNetworkError(): void
    {
        $mock = new MockHandler([
            new ConnectException('Connection refused', new Request('POST', 'auth/token')),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $client = new GuzzleHttpClient($guzzleClient);

        $this->expectException(GoSmsRequestException::class);
        $this->expectExceptionMessage('Network error: Connection refused');

        $client->request('POST', 'auth/token');
    }

    public function testRequestThrowsOnInvalidJson(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'invalid json'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $client = new GuzzleHttpClient($guzzleClient);

        $this->expectException(\JsonException::class);

        $client->request('GET', 'messages');
    }

    public function testRequestGetWithQueryParams(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{"custom_id":"id-1","total_count":0,"messages":[]}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $client = new GuzzleHttpClient($guzzleClient);

        $result = $client->request('GET', 'messages/by-custom-id/id-1', ['custom_id' => 'id-1']);

        self::assertSame(200, $result['status']);
        self::assertSame('id-1', $result['body']['custom_id']);
        self::assertSame(0, $result['body']['total_count']);
    }

    public function testRequestReturnsEmptyArrayWhenResponseBodyIsNullJson(): void
    {
        $mock = new MockHandler([
            new Response(200, [], 'null'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);
        $client = new GuzzleHttpClient($guzzleClient);

        $result = $client->request('GET', 'messages');

        self::assertSame(200, $result['status']);
        self::assertSame([], $result['body']);
    }

}
