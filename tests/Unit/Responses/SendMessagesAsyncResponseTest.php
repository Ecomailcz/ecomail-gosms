<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Responses;

use EcomailGoSms\Responses\SendMessagesAsyncResponse;
use EcomailGoSms\Tests\GoSmsClientTestUtility;
use Mockery;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

final class SendMessagesAsyncResponseTest extends TestCase
{

    use GoSmsClientTestUtility;

    public function testGetAccepted(): void
    {
        $response = $this->createSendMessagesAsyncResponse();
        $accepted = $response->getAccepted();

        self::assertCount(2, $accepted);
        self::assertSame('accepted', $accepted[0]->status);
        self::assertSame('+420733382412', $accepted[0]->recipient);
        self::assertSame('6953ab3ad4eb3', $accepted[0]->customId);
        self::assertSame('/api/v2/messages/by-custom-id/6953ab3ad4eb3', $accepted[0]->link);
    }

    public function testGetRejected(): void
    {
        $response = $this->createSendMessagesAsyncResponse();
        $rejected = $response->getRejected();

        self::assertCount(0, $rejected);
    }

    public function testGetTotalAccepted(): void
    {
        $response = $this->createSendMessagesAsyncResponse();

        self::assertSame(2, $response->getTotalAccepted());
    }

    public function testGetTotalRejected(): void
    {
        $response = $this->createSendMessagesAsyncResponse();

        self::assertSame(0, $response->getTotalRejected());
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function createSendMessagesAsyncResponse(): SendMessagesAsyncResponse
    {
        $json = (string) file_get_contents(__DIR__ . '/../../Fixtures/send_messages_async.json');
        $mockResponse = $this->createMockResponseWithBody($json);

        return new SendMessagesAsyncResponse($mockResponse);
    }

}
