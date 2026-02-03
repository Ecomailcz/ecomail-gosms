<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Requests;

use EcomailGoSms\Requests\AuthenticationRequest;
use EcomailGoSms\Requests\Request;
use PHPUnit\Framework\TestCase;

final class AuthenticationRequestTest extends TestCase
{

    public function testUsesApiV2(): void
    {
        self::assertStringStartsWith('/api/v', Request::API_PATH);
        self::assertStringContainsString('v2', Request::API_PATH);
    }

    public function testGetMethod(): void
    {
        $request = new AuthenticationRequest('public-key', 'private-key');
        
        self::assertSame('POST', $request->getMethod());
    }

    public function testGetEndpoint(): void
    {
        $request = new AuthenticationRequest('public-key', 'private-key');
        
        self::assertSame('https://api.gosms.eu/api/v2/auth/token', $request->getEndpoint());
    }

    public function testGetOptions(): void
    {
        $request = new AuthenticationRequest('test-public', 'test-private');
        $options = $request->getOptions();
        
        self::assertArrayHasKey('form_params', $options);
        
        /** @var array<string, mixed> $formParams */
        $formParams = $options['form_params'];
        self::assertSame('test-public', $formParams['client_id']);
        self::assertSame('test-private', $formParams['client_secret']);
        self::assertSame('password', $formParams['grant_type']);
        self::assertSame('test-private', $formParams['password']);
        self::assertSame('test-public', $formParams['username']);
    }

}
