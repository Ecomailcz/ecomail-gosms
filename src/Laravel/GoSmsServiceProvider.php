<?php

declare(strict_types = 1);

namespace EcomailGoSms\Laravel;

use EcomailGoSms\Client;
use EcomailGoSms\GoSmsClient;
use EcomailGoSms\Requests\Request;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class GoSmsServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/gosms.php', 'gosms');

        $this->app->singleton(GuzzleClient::class, static fn (): GuzzleClient => new GuzzleClient([
            'base_uri' => Request::BASE_URL,
            'timeout' => 10,
        ]));

        $this->app->singleton(Client::class, static function (Container $app): GoSmsClient {
            $publicKey = config()->string('gosms.client_id');
            $privateKey = config()->string('gosms.client_secret');
            $defaultChannel = config()->integer('gosms.default_channel');

            $httpClient = $app->make(GuzzleClient::class);

            /** @phpstan-ignore instanceof.alwaysTrue (binding can be overridden in tests) */
            if (!$httpClient instanceof GuzzleClient) {
                throw new \InvalidArgumentException('Invalid HTTP client instance');
            }

            return new GoSmsClient(
                $publicKey,
                $privateKey,
                null,
                $defaultChannel,
                'password',
                '',
                $httpClient,
            );
        });

        $this->app->alias(Client::class, GoSmsClient::class);
        $this->app->alias(GoSmsClient::class, 'gosms');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/gosms.php' => config_path('gosms.php'),
        ], ['config', 'gosms-config']);

        if ($this->app->runningInConsole()) {
            $this->commands([InstallGoSmsCommand::class]);
        }
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [Client::class, 'gosms'];
    }

}
