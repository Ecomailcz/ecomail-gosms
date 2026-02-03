<?php

declare(strict_types = 1);

namespace EcomailGoSms\Tests;

use EcomailGoSms\Laravel\GoSmsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [GoSmsServiceProvider::class];
    }

}
