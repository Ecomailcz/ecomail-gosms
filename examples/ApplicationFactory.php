<?php

declare(strict_types = 1);

use EcomailGoSms\GoSmsClient;
use EcomailGoSms\Tests\TestCase;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Application;

final class ApplicationFactory extends TestCase
{

    public function createApp(): Application
    {
        return $this->createApplication();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $app['config'];

        $config->set('gosms.client_id', $this->envString('GOSMS_CLIENT_ID'));
        $config->set('gosms.client_secret', $this->envString('GOSMS_CLIENT_SECRET'));

        $channelId = $this->envChannelId();

        if ($channelId !== null) {
            $config->set('gosms.default_channel', $channelId);
        }

        $app->singleton('gosms.authenticated', static function () use ($app): GoSmsClient {
            $client = $app->make(GoSmsClient::class);
            $auth = $client->authenticate();
            $defaultChannel = config()->get('gosms.default_channel');

            return new GoSmsClient(
                config()->string('gosms.client_id'),
                config()->string('gosms.client_secret'),
                $auth->getAccessToken(),
                is_int($defaultChannel) ? $defaultChannel : null,
                'password',
                '',
                $app->make(GuzzleClient::class),
            );
        });
    }

    private function envString(string $key): string
    {
        $value = $_ENV[$key] ?? null;

        return isset($value) && is_string($value) ? $value : '';
    }

    private function envChannelId(): ?int
    {
        $raw = $_ENV['GOSMS_CHANNEL_ID'] ?? null;

        if (!is_scalar($raw)) {
            return null;
        }

        $str = (string) $raw;

        return $str !== '' && ctype_digit($str) ? (int) $str : null;
    }

}
