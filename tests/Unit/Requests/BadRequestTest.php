<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Requests;

use EcomailGoSms\Exceptions\BadRequest;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class BadRequestTest extends TestCase
{

    public function testGetResponse(): void
    {
        $response = $this->mockResponse(400, 'Bad Request');
        $exception = new BadRequest($response);
        
        self::assertSame($response, $exception->getResponse());
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
