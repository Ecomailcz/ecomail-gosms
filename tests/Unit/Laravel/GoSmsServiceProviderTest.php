<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests\Unit\Laravel;

use EcomailGoSms\Client;
use EcomailGoSms\GoSmsClient;
use EcomailGoSms\Laravel\GoSmsServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

afterEach(function (): void {
    Config::set('gosms.client_id', 'test-client-id');
    Config::set('gosms.client_secret', 'test-client-secret');
    Config::set('gosms.default_channel', 1);
});

it('throws when client id is not string', function (): void {
    Config::set('gosms.client_id');

    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    expect(static fn () => $app->make(Client::class))
        ->toThrow(\InvalidArgumentException::class, 'Configuration value for key [gosms.client_id] must be a string, NULL given.');
});

it('throws when client secret is not string', function (): void {
    Config::set('gosms.client_secret', 123);

    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    expect(static fn () => $app->make(Client::class))
        ->toThrow(\InvalidArgumentException::class, 'Configuration value for key [gosms.client_secret] must be a string, integer given.');
});

it('throws when default channel is not int', function (): void {
    Config::set('gosms.default_channel', '1');

    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    expect(static fn () => $app->make(Client::class))
        ->toThrow(\InvalidArgumentException::class, 'Configuration value for key [gosms.default_channel] must be an integer, string given.');
});

it('throws when default channel is missing', function (): void {
    Config::set('gosms.default_channel');

    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    expect(static fn () => $app->make(Client::class))
        ->toThrow(\InvalidArgumentException::class);
});

it('throws when http client is not http client instance', function (): void {
    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    $app->bind(\GuzzleHttp\Client::class, static fn (): \stdClass => new \stdClass());

    expect(static fn () => $app->make(Client::class))
        ->toThrow(\InvalidArgumentException::class, 'Invalid HTTP client instance');
});

it('resolves client as singleton', function (): void {
    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    $first = $app->make(GoSmsClient::class);
    $second = $app->make(GoSmsClient::class);

    expect($first)->toBe($second);
});

it('resolves alias to client', function (): void {
    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    $client = $app->make('gosms');

    expect($client)->toBeInstanceOf(GoSmsClient::class);
});

it('resolves client with default channel from config', function (): void {
    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    $client = $app->make(Client::class);

    expect($client->getDefaultChannel())->toBe(1);
});

it('publishes config on vendor publish', function (): void {
    $targetPath = config_path('gosms.php');

    if (File::exists($targetPath)) {
        File::delete($targetPath);
    }

    Artisan::call('vendor:publish', [
        '--provider' => GoSmsServiceProvider::class,
        '--tag' => 'config',
        '--force' => true,
    ]);

    expect($targetPath)->toBeFile();
});

it('publishes config via gosms:install command', function (): void {
    $targetPath = config_path('gosms.php');

    if (File::exists($targetPath)) {
        File::delete($targetPath);
    }

    Artisan::call('gosms:install');

    expect($targetPath)->toBeFile();
});

it('provides client and alias', function (): void {
    /** @var \Illuminate\Foundation\Application $app */
    $app = app();

    $provider = $app->getProvider(GoSmsServiceProvider::class);

    expect($provider)->toBeInstanceOf(GoSmsServiceProvider::class)
        ->and($provider?->provides())->toBe([Client::class, 'gosms']);
});
