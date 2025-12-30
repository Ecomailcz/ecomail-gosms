<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use function sprintf;

abstract class GeneralException extends Exception
{

    public function __construct(private readonly ResponseInterface $response, null|string|Throwable $messageOrPrevious = null)
    {
        if (is_string($messageOrPrevious)) {
            $message = $messageOrPrevious;
            $code = $this->response->getStatusCode();
            $previous = null;
        } elseif ($messageOrPrevious instanceof Throwable) {
            $message = $messageOrPrevious->getMessage() !== ''
                ? $messageOrPrevious->getMessage()
                : sprintf(
                    '"%s"',
                    $this->response->getBody()->getContents(),
                );
            $code = (int) $messageOrPrevious->getCode() !== 0 ? (int) $messageOrPrevious->getCode() : $this->response->getStatusCode();
            $previous = $messageOrPrevious;
        } else {
            $message = sprintf('"%s"', $this->response->getBody()->getContents());
            $code = $this->response->getStatusCode();
            $previous = null;
        }

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

}
