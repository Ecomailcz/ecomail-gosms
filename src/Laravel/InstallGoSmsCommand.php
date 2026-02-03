<?php

declare(strict_types = 1);

namespace EcomailGoSms\Laravel;

use Illuminate\Console\Command;

final class InstallGoSmsCommand extends Command
{

    /** @var string */
    protected $signature = 'gosms:install';

    /** @var string */
    protected $description = 'Publish GoSms config and display .env setup instructions';

    public function handle(): int
    {
        $this->info('Publishing GoSms config...');

        $this->call('vendor:publish', [
            '--force' => false,
            '--provider' => GoSmsServiceProvider::class,
            '--tag' => 'gosms-config',
        ]);

        $this->newLine();
        $this->comment('Add the following to your .env file:');
        $this->line('GOSMS_CLIENT_ID=');
        $this->line('GOSMS_CLIENT_SECRET=');
        $this->line('GOSMS_DEFAULT_CHANNEL=1');
        $this->newLine();

        return self::SUCCESS;
    }

}
