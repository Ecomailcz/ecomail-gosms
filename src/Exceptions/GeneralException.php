<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use function sprintf;

abstract class GeneralException extends Exception
{

    public function __construct(private readonly ResponseInterface $response, ?Throwable $previous = null)
    {
        $message = $previous?->getMessage() ?? sprintf('"%s"', $this->response->getBody()->getContents());
        $code = $previous?->getCode() ?? $this->response->getStatusCode();

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

}
