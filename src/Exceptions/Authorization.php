<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

final class Authorization extends GoSmsException
{

    public static function invalidCredentials(): self
    {
        return new self('Invalid GoSms credentials provided');
    }

    public static function authenticationFailed(): self
    {
        return new self('GoSms authentication failed');
    }

}
