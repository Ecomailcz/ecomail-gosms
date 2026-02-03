<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

final class InvalidFormat extends GoSmsException
{

    public static function invalidMessageFormat(string $message): self
    {
        return new self('Invalid message format: ' . $message);
    }

    public static function invalidPhoneNumber(string $message): self
    {
        return new self('Invalid phone number: ' . $message);
    }

}
