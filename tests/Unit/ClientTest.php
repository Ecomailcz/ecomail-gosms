<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit;

use EcomailGoSms\Exceptions\BadRequest;
use EcomailGoSms\Exceptions\InvalidRequest;
use EcomailGoSms\Exceptions\UnauthorizedRequest;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Throwable;

use function file_get_contents;

final class ClientTest extends TestCase
{

    use GoSmsClientTestUtility;

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function testRefreshTokenInvalidRequest(): void
    {
        $body = file_get_contents(__DIR__ . '/../Fixtures/refresh_token_validation_error.json');
        $response = new Response(422, [], body: $body !== false ? $body : '');
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/auth/refresh');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);

        $this->expectException(InvalidRequest::class);
        $client->refreshToken('xxxx');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function testRefreshToken(): void
    {
        $client = $this->createGoSmsClientWithJsonResponse(__DIR__ . '/../Fixtures/refresh_token.json');
        $response = $client->refreshToken('xxxxx');
        
        self::assertSame('string', $response->getAccessToken());
        self::assertSame('string', $response->getRefreshToken());
        self::assertSame('Bearer', $response->getTokenType());
    }

    public function testAuthenticate(): void
    {
        $client = $this->createGoSmsClientWithJsonResponse(__DIR__ . '/../Fixtures/authenticate.json');
        $response = $client->authenticate();

        self::assertSame('string', $response->getAccessToken());
    }

    public function testAuthenticateBadRequest(): void
    {
        $this->expectException(BadRequest::class);
        
        $response = new Response(400, [], 'Error');
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/auth/token');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);
        
        $client->authenticate();
    }

    public function testAuthenticateUnauthorized(): void
    {
        $this->expectException(UnauthorizedRequest::class);
        
        $response = new Response(401, [], 'Error');
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/auth/token');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);
        
        $client->authenticate();
    }

    public function testAuthenticateInvalidRequest(): void
    {
        $this->expectException(InvalidRequest::class);
        
        $body = file_get_contents(__DIR__ . '/../Fixtures/authenticate_validation_error.json');
        $response = new Response(422, [], body: $body !== false ? $body : '');
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/auth/token');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);
        
        $client->authenticate();
    }

    public function testAuthenticateClientException(): void
    {
        $this->expectException(ClientException::class);
        
        $response = new Response(500, [], 'Error');
        $request = new Request('POST', 'https://api.gosms.eu/api/v2/auth/token');
        $exception = new ClientException('Error', $request, $response);
        $client = $this->createGoSmsClientWithException($exception);
        
        $client->authenticate();
    }

    public function testAuthenticateOtherException(): void
    {
        $this->expectException(Throwable::class);
        $client = $this->createGoSmsClientWithException(new Exception());
        
        $client->authenticate();
    }

    public function testAuthenticateWithAccessToken(): void
    {
        $client = $this->createGoSmsClientWithJsonResponseAndAccessToken(__DIR__ . '/../Fixtures/authenticate.json', 'existing-token');

        $response = $client->authenticate();

        self::assertSame('string', $response->getAccessToken());
    }

    public function testGetAccessTokenWithToken(): void
    {
        $client = $this->createGoSmsClientWithAccessToken('access-token');
        
        self::assertSame('access-token', $client->getAccessToken());
    }

    public function testGetAccessTokenWithoutToken(): void
    {
        $client = $this->createGoSmsClientWithAccessToken(null);
        
        self::assertNull($client->getAccessToken());
    }

}
