<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Data;

use EcomailGoSms\Data\BulkSmsResponse;
use EcomailGoSms\Data\SmsResponse;
use PHPUnit\Framework\TestCase;

final class BulkSmsResponseTest extends TestCase
{

    public function testConstructor(): void
    {
        $results = [
            new SmsResponse(1, 'sent'),
            new SmsResponse(2, 'failed', 'Invalid number'),
        ];
        $response = new BulkSmsResponse($results, 2, 1, 1);

        self::assertCount(2, $response->results);
        self::assertSame(2, $response->totalCount);
        self::assertSame(1, $response->successCount);
        self::assertSame(1, $response->errorCount);
        self::assertSame('sent', $response->results[0]->status);
        self::assertSame('failed', $response->results[1]->status);
    }

}
