<?php

declare(strict_types = 1);

namespace EcomailGoSms\Laravel;

use Illuminate\Support\Facades\Facade;

final class GoSmsFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'gosms';
    }

}
