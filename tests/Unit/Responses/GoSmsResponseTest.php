<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Responses;

use EcomailGoSms\Exceptions\InvalidResponseData;
use EcomailGoSms\Responses\GoSmsResponse;
use Iterator;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class GoSmsResponseTest extends TestCase
{

    #[DataProvider('jsonDataProvider')]
    public function testBodyContentsToArray(string $jsonData, bool $shouldThrowException): void
    {
        $responseMock = $this->createMockResponseWithJsonData($jsonData, $shouldThrowException);
        $testResponse = $this->createTestGoSmsResponse($responseMock);
        
        if ($shouldThrowException) {
            $this->expectException(InvalidResponseData::class);
            $testResponse->testBodyContentsToArray();
        } else {
            $result = $testResponse->testBodyContentsToArray();
            
            if ($jsonData === 'null') {
                self::assertEmpty($result);
            } else {
                self::assertSame('value', $result['key']);
                self::assertSame(123, $result['number']);
            }
        }
    }

    #[DataProvider('stringDataProvider')]
    public function testGetStringByKey(string $jsonData, string $key, mixed $expectedValue): void
    {
        $responseMock = $this->createMockResponseWithJsonData($jsonData);
        $testResponse = $this->createTestGoSmsResponse($responseMock);

        if ($expectedValue === null) {
            $this->expectException(InvalidResponseData::class);
            $testResponse->testGetStringByKey($key);
        } else {
            $result = $testResponse->testGetStringByKey($key);
            self::assertSame($expectedValue, $result);
        }
    }

    #[DataProvider('integerDataProvider')]
    public function testGetIntegerByKey(string $jsonData, string $key, mixed $expectedValue): void
    {
        $responseMock = $this->createMockResponseWithJsonData($jsonData);
        $testResponse = $this->createTestGoSmsResponse($responseMock);

        if ($expectedValue === null) {
            $this->expectException(InvalidResponseData::class);
            $testResponse->testGetIntegerByKey($key);
        } else {
            $result = $testResponse->testGetIntegerByKey($key);
            self::assertSame($expectedValue, $result);
        }
    }

    #[DataProvider('dataByKeyProvider')]
    public function testGetDataByKey(string $jsonData, string $key, mixed $expectedValue): void
    {
        $responseMock = $this->createMockResponseWithJsonData($jsonData);
        $testResponse = $this->createTestGoSmsResponse($responseMock);
        $result = $testResponse->testGetDataByKey($key);

        self::assertSame($expectedValue, $result);
    }

    #[DataProvider('arrayDataProvider')]
    public function testGetArrayByKey(string $jsonData, string $key, mixed $expectedValue): void
    {
        $responseMock = $this->createMockResponseWithJsonData($jsonData);
        $testResponse = $this->createTestGoSmsResponse($responseMock);

        if ($expectedValue === null) {
            $this->expectException(InvalidResponseData::class);
            $testResponse->testGetArrayByKey($key);
        } else {
            $result = $testResponse->testGetArrayByKey($key);
            self::assertSame($expectedValue, $result);
        }
    }

    public function testBodyContentsToArrayCaching(): void
    {
        $responseMock = Mockery::mock(ResponseInterface::class);
        $bodyMock = Mockery::mock(StreamInterface::class);
        
        $responseMock->allows('getBody')->andReturns($bodyMock);
        $bodyMock->allows('getContents')->andReturn('{"key": "value"}');
        
        $testResponse = new TestGoSmsResponse($responseMock);

        $result1 = $testResponse->testBodyContentsToArray();
        $result2 = $testResponse->testBodyContentsToArray();
        
        self::assertSame($result1, $result2);
        self::assertSame('value', $result1['key']);
        self::assertSame('value', $result2['key']);
    }

    public function testGetResponse(): void
    {
        $responseMock = $this->createMockResponseWithJsonData('{"key": "value"}');
        $testResponse = $this->createTestGoSmsResponse($responseMock);

        self::assertSame($responseMock, $testResponse->getResponse());
    }

    /**
     * @return \Iterator<string, array{string, bool}>
     */
    public static function jsonDataProvider(): Iterator
    {
        yield 'valid json' => ['{"key": "value", "number": 123}', false];
        yield 'null json' => ['null', false];
        yield 'invalid json' => ['invalid json', true];
    }

    /**
     * @return \Iterator<string, array{string, string, mixed}>
     */
    public static function stringDataProvider(): Iterator
    {
        yield 'valid string' => ['{"name": "John", "age": 30}', 'name', 'John'];
        yield 'invalid type' => ['{"name": 123}', 'name', null];
    }

    /**
     * @return \Iterator<string, array{string, string, mixed}>
     */
    public static function integerDataProvider(): Iterator
    {
        yield 'valid integer' => ['{"age": 30, "name": "John"}', 'age', 30];
        yield 'invalid type' => ['{"age": "thirty"}', 'age', null];
    }

    /**
     * @return \Iterator<string, array{string, string, mixed}>
     */
    public static function arrayDataProvider(): Iterator
    {
        yield 'valid array' => ['{"items": ["a", "b"], "name": "John"}', 'items', ['a', 'b']];
        yield 'invalid type' => ['{"items": "not_array"}', 'items', null];
    }

    /**
     * @return \Iterator<string, array{string, string, mixed}>
     */
    public static function dataByKeyProvider(): Iterator
    {
        yield 'string value' => ['{"name": "John"}', 'name', 'John'];
        yield 'integer value' => ['{"age": 30}', 'age', 30];
        yield 'float value' => ['{"price": 19.99}', 'price', 19.99];
        yield 'boolean value' => ['{"active": true}', 'active', true];
        yield 'null value' => ['{"missing": null}', 'missing', null];
        yield 'missing key' => ['{"existing": "value"}', 'missing', null];
    }

    private function createMockResponseWithJsonData(string $jsonData, bool $shouldThrowException = false): ResponseInterface
    {
        $responseMock = Mockery::mock(ResponseInterface::class);
        $bodyMock = Mockery::mock(StreamInterface::class);

        $responseMock->allows('getBody')->andReturns($bodyMock);
        $responseMock->allows('getStatusCode')->andReturn($shouldThrowException ? 500 : 200);
        $bodyMock->allows('getContents')->andReturn($jsonData);

        return $responseMock;
    }

    private function createTestGoSmsResponse(ResponseInterface $responseMock): TestGoSmsResponse
    {
        return new TestGoSmsResponse($responseMock);
    }

}

/**
 * Test implementation of GoSmsResponse for testing abstract methods
 */
final class TestGoSmsResponse extends GoSmsResponse
{

    /**
     * @return array<string, mixed>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function testBodyContentsToArray(): array
    {
        return $this->bodyContentsToArray();
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function testGetStringByKey(string $key): string
    {
        return $this->getStringByKey($key);
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function testGetIntegerByKey(string $key): int
    {
        return $this->getIntegerByKey($key);
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function testGetDataByKey(string $key): mixed
    {
        return $this->getDataByKey($key);
    }

    /**
     * @return array<mixed, mixed>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function testGetArrayByKey(string $key): array
    {
        return $this->getArrayByKey($key);
    }

}
