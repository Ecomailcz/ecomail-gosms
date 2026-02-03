<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Exceptions;

use EcomailGoSms\Exceptions\InvalidResponseData;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class InvalidResponseDataTest extends TestCase
{

    public function testConstructorWithStringMessage(): void
    {
        $responseMock = $this->createMockResponse('{"error": "test"}', 400);
        $exception = new InvalidResponseData($responseMock);

        self::assertSame('"{"error": "test"}"', $exception->getMessage());
        self::assertSame(400, $exception->getCode());
        self::assertSame($responseMock, $exception->getResponse());
        self::assertSame(['error' => 'test'], $exception->getResponseData());
    }

    public function testConstructorWithThrowable(): void
    {
        $responseMock = $this->createMockResponse('{"error": "test"}', 500);
        $previousException = new RuntimeException('Previous error', 123);

        $exception = new InvalidResponseData($responseMock, $previousException);

        self::assertSame('Previous error', $exception->getMessage());
        self::assertSame(123, $exception->getCode());
        self::assertSame($previousException, $exception->getPrevious());
        self::assertSame($responseMock, $exception->getResponse());
    }

    public function testConstructorWithThrowableEmptyMessage(): void
    {
        $responseMock = $this->createMockResponse('{"error": "test"}', 400);

        $previousException = new class () extends Exception {

            public function __construct()
            {
                parent::__construct('', 0);
            }
        
        };

        $exception = new InvalidResponseData($responseMock, $previousException);

        self::assertSame('', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($previousException, $exception->getPrevious());
        self::assertSame($responseMock, $exception->getResponse());
    }

    public function testConstructorWithThrowableZeroCode(): void
    {
        $responseMock = $this->createMockResponse('{"error": "test"}', 422);

        $previousException = new class () extends Exception {

            public function __construct()
            {
                parent::__construct('Error message', 0);
            }
        
        };

        $exception = new InvalidResponseData($responseMock, $previousException);

        self::assertSame('Error message', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($previousException, $exception->getPrevious());
        self::assertSame($responseMock, $exception->getResponse());
    }

    public function testConstructorWithoutParameters(): void
    {
        $responseMock = $this->createMockResponse('{"error": "test"}', 404);
        $exception = new InvalidResponseData($responseMock);

        self::assertStringStartsWith('"{"error": "test"}"', $exception->getMessage());
        self::assertSame(404, $exception->getCode());
        self::assertSame($responseMock, $exception->getResponse());
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function createMockResponse(string $bodyContent, int $statusCode): ResponseInterface
    {
        $responseMock = Mockery::mock(ResponseInterface::class);
        $bodyMock = Mockery::mock(StreamInterface::class);

        $responseMock->allows('getBody')->andReturns($bodyMock);
        $responseMock->allows('getStatusCode')->andReturn($statusCode);
        $bodyMock->allows('getContents')->andReturn($bodyContent);

        return $responseMock;
    }

}
