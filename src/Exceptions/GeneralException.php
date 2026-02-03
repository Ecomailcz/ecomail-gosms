<?php

declare(strict_types = 1);

namespace EcomailGoSms\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use function sprintf;

abstract class GeneralException extends Exception
{

    /** @phpstan-ignore property.unusedType */
    private readonly ?string $responseContents;

    public function __construct(private readonly ResponseInterface $response, ?Throwable $previous = null, ?string $message = null)
    {
        $this->responseContents = $this->response->getBody()->getContents();

        $message ??= $previous?->getMessage() ?? sprintf('"%s"', $this->responseContents);

        $code = $previous?->getCode() ?? $this->response->getStatusCode();

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @throws \JsonException
     */
    public function getResponseData(): mixed
    {
        return json_decode($this->responseContents, true, 512, JSON_THROW_ON_ERROR);
    }

}
