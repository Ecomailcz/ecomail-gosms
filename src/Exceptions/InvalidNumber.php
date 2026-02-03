<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

final class InvalidNumber extends GoSmsException
{

    public static function invalidPhoneNumber(string $number): self
    {
        return new self('Invalid phone number format: ' . $number);
    }

}
