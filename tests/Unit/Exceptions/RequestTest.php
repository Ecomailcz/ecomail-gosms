<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Exceptions;

use EcomailGoSms\Exceptions\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{

    public function testHttpError(): void
    {
        $exception = Request::httpError(500);

        self::assertSame('HTTP request failed with status 500', $exception->getMessage());
    }

    public function testHttpErrorWithMessage(): void
    {
        $exception = Request::httpError(404, 'Not found');

        self::assertSame('HTTP request failed with status 404: Not found', $exception->getMessage());
    }

    public function testNetworkError(): void
    {
        $exception = Request::networkError('Connection refused');

        self::assertSame('Network error: Connection refused', $exception->getMessage());
    }

}
