<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Exceptions;

use EcomailGoSms\Exceptions\InvalidRequest;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class InvalidRequestTest extends TestCase
{

    public function testGetResponse(): void
    {
        $response = $this->mockResponse(422, 'Validation Error');
        $exception = new InvalidRequest($response);

        self::assertSame($response, $exception->getResponse());
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function mockResponse(int $statusCode, string $bodyContent): ResponseInterface
    {
        $body = Mockery::mock(StreamInterface::class);
        $body->allows('getContents')->andReturn($bodyContent);

        $response = Mockery::mock(ResponseInterface::class);
        $response->allows('getStatusCode')->andReturn($statusCode);
        $response->allows('getBody')->andReturns($body);

        return $response;
    }

}
