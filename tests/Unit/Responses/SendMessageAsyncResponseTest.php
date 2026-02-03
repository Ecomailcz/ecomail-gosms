<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Responses;

use EcomailGoSms\Exceptions\InvalidResponseData;
use EcomailGoSms\Responses\SendMessageAsyncResponse;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use Mockery;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

final class SendMessageAsyncResponseTest extends TestCase
{

    use GoSmsClientTestUtility;

    public function testGetStatus(): void
    {
        $response = $this->createSendMessageAsyncResponse();

        self::assertSame('accepted', $response->getStatus());
    }

    public function testGetRecipient(): void
    {
        $response = $this->createSendMessageAsyncResponse();

        self::assertSame('+420733382412', $response->getRecipient());
    }

    public function testGetCustomId(): void
    {
        $response = $this->createSendMessageAsyncResponse();

        self::assertSame('695395e54046d', $response->getCustomId());
    }

    public function testGetLink(): void
    {
        $response = $this->createSendMessageAsyncResponse();

        self::assertSame('/api/v2/messages/by-custom-id/695395e54046d', $response->getLink());
    }

    public function testValidationError(): void
    {
        $response = $this->createSendMessageAsyncInvalidResponse();
        $this->expectException(InvalidResponseData::class);
        $response->getStatus();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function createSendMessageAsyncResponse(): SendMessageAsyncResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/send_message_async_success.json');
        $mockResponse = $this->createMockResponseWithBody($json);

        return new SendMessageAsyncResponse($mockResponse);
    }

    private function createSendMessageAsyncInvalidResponse(): SendMessageAsyncResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/send_message_async_validation_error.json');
        $mockResponse = $this->createMockResponseWithBody($json, statusCode: 422);

        return new SendMessageAsyncResponse($mockResponse);
    }

}
