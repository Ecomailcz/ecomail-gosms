<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Requests;

use EcomailGoSms\Requests\RefreshAccessTokenRequest;
use PHPUnit\Framework\TestCase;

final class RefreshAccessTokenRequestTest extends TestCase
{

    public function testGetMethod(): void
    {
        $request = new RefreshAccessTokenRequest('refresh-token');

        self::assertSame('POST', $request->getMethod());
    }

    public function testGetEndpoint(): void
    {
        $request = new RefreshAccessTokenRequest('refresh-token');

        self::assertSame('https://api.gosms.eu/api/v2/auth/refresh', $request->getEndpoint());
    }

    public function testGetOptions(): void
    {
        $request = new RefreshAccessTokenRequest('my-refresh-token');
        $options = $request->getOptions();

        self::assertArrayHasKey('form_params', $options);

        /** @var array<string, mixed> $formParams */
        $formParams = $options['form_params'];
        self::assertSame('my-refresh-token', $formParams['refresh_token']);
    }

}
