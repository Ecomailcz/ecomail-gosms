<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

final class Request extends GoSmsException
{

    public static function httpError(int $statusCode, string $message = ''): self
    {
        return new self('HTTP request failed with status ' . $statusCode . ($message !== '' ? ': ' . $message : ''));
    }

    public static function networkError(string $message): self
    {
        return new self('Network error: ' . $message);
    }

}
