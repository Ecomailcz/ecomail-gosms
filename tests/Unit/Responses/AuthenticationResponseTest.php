<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Responses;

use EcomailGoSms\Exceptions\InvalidResponseData;
use EcomailGoSms\Responses\AuthenticationResponse;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use Mockery;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

final class AuthenticationResponseTest extends TestCase
{

    use GoSmsClientTestUtility;

    public function testGetAccessToken(): void
    {
        $response = $this->createAuthenticationResponse();
        
        self::assertSame('string', $response->getAccessToken());
    }

    public function testGetRefreshToken(): void
    {
        $response = $this->createAuthenticationResponse();

        self::assertSame('string', $response->getRefreshToken());
    }

    public function testGetTokenType(): void
    {
        $response = $this->createAuthenticationResponse();

        self::assertSame('Bearer', $response->getTokenType());
    }

    public function testValidationError(): void
    {
        $response = $this->createAuthenticationInvalidResponse();
        $this->expectException(InvalidResponseData::class);
        $response->getAccessToken();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function createAuthenticationResponse(): AuthenticationResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/authenticate.json');
        $mockResponse = $this->createMockResponseWithBody($json);

        return new AuthenticationResponse($mockResponse);
    }

    private function createAuthenticationInvalidResponse(): AuthenticationResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/authenticate_validation_error.json');
        $mockResponse = $this->createMockResponseWithBody($json, statusCode: 422);

        return new AuthenticationResponse($mockResponse);
    }

}
