<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests;

use EcomailGoSms\GoSmsClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

use function file_get_contents;

trait GoSmsClientTestUtility
{

    /**
     * Creates GoSmsClient with mock response from JSON file
     */
    protected function createGoSmsClientWithJsonResponse(
        string $jsonFilePath,
        int $statusCode = 200,
        string $publicKey = 'public-key',
        string $privateKey = 'private-key',
    ): GoSmsClient {
        $guzzleClient = Mockery::mock(Client::class);
        $guzzleClient->allows('request')->andReturnUsing(
            static fn (): Response => new Response(
                status: $statusCode,
                body: (string) file_get_contents($jsonFilePath),
            ),
        );

        return new GoSmsClient($publicKey, $privateKey, httpClient: $guzzleClient);
    }

    /**
     * Creates mock ResponseInterface with given body and statusCode
     */
    protected function createMockResponseWithBody(string $body, int $statusCode = 200): ResponseInterface
    {
        $responseMock = Mockery::mock(ResponseInterface::class);
        $bodyMock = Mockery::mock(StreamInterface::class);
        
        $responseMock->allows('getBody')->andReturns($bodyMock);
        $responseMock->allows('getStatusCode')->andReturn($statusCode);
        $bodyMock->allows('getContents')->andReturn($body);
        
        return $responseMock;
    }

    /**
     * Creates GoSmsClient that throws exception
     */
    protected function createGoSmsClientWithException(
        Throwable $throwable,
        string $publicKey = 'public-key',
        string $privateKey = 'private-key',
    ): GoSmsClient {
        $guzzleClient = Mockery::mock(Client::class)->makePartial();
        $guzzleClient->allows('request')->andThrow($throwable);

        return new GoSmsClient($publicKey, $privateKey, httpClient: $guzzleClient);
    }

    /**
     * Creates GoSmsClient with access token
     */
    protected function createGoSmsClientWithAccessToken(
        ?string $accessToken = 'test-access-token',
        string $publicKey = 'public-key',
        string $privateKey = 'private-key',
    ): GoSmsClient {
        $guzzleClient = Mockery::mock(Client::class);

        return new GoSmsClient($publicKey, $privateKey, $accessToken, httpClient: $guzzleClient);
    }

    /**
     * Creates GoSmsClient with JSON response and access token
     */
    protected function createGoSmsClientWithJsonResponseAndAccessToken(
        string $jsonFilePath,
        string $accessToken = 'test-access-token',
        int $statusCode = 200,
        string $publicKey = 'public-key',
        string $privateKey = 'private-key',
    ): GoSmsClient {
        $guzzleClient = Mockery::mock(Client::class);
        $guzzleClient->allows('request')->andReturnUsing(
            static fn (): Response => new Response(
                status: $statusCode,
                body: (string) file_get_contents($jsonFilePath),
            ),
        );

        return new GoSmsClient($publicKey, $privateKey, $accessToken, httpClient: $guzzleClient);
    }

}
