<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Responses;

use EcomailGoSms\Exceptions\InvalidResponseData;
use EcomailGoSms\Responses\RefreshAccessTokenResponse;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use Mockery;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

final class RefreshAccessTokenResponseTest extends TestCase
{

    use GoSmsClientTestUtility;

    public function testGetAccessToken(): void
    {
        $response = $this->createRefreshAccessTokenResponse();

        self::assertSame('string', $response->getAccessToken());
    }

    public function testGetRefreshToken(): void
    {
        $response = $this->createRefreshAccessTokenResponse();

        self::assertSame('string', $response->getRefreshToken());
    }

    public function testGetTokenType(): void
    {
        $response = $this->createRefreshAccessTokenResponse();

        self::assertSame('Bearer', $response->getTokenType());
    }

    public function testValidationError(): void
    {
        $response = $this->createRefreshAccessTokenInvalidResponse();
        $this->expectException(InvalidResponseData::class);
        $response->getAccessToken();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function createRefreshAccessTokenResponse(): RefreshAccessTokenResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/refresh_token.json');
        $mockResponse = $this->createMockResponseWithBody($json);

        return new RefreshAccessTokenResponse($mockResponse);
    }

    private function createRefreshAccessTokenInvalidResponse(): RefreshAccessTokenResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/refresh_token_validation_error.json');
        $mockResponse = $this->createMockResponseWithBody($json, statusCode: 422);

        return new RefreshAccessTokenResponse($mockResponse);
    }

}
