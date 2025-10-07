<?php

declare(strict_types = 1);

namespace EcomailGoSms\Responses;

use EcomailGoSms\Exceptions\InvalidResponseData;
use JsonException;
use Psr\Http\Message\ResponseInterface;

use function assert;
use function gettype;
use function is_int;
use function is_string;
use function json_decode;
use function sprintf;

abstract class GoSmsResponse
{

    private ?string $cachedBody = null;

    public function __construct(protected ResponseInterface $response)
    {
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array<string, mixed>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function bodyContentsToArray(): array
    {
        try {
            $this->cachedBody ??= $this->getResponse()->getBody()->getContents();
            
            /** @var array<string, mixed>|null $decoded */
            $decoded = json_decode($this->cachedBody, true, 512, JSON_THROW_ON_ERROR);

            return $decoded ?? [];
        } catch (JsonException) {
            throw new InvalidResponseData($this->getResponse());
        }
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getStringByKey(string $key): string
    {
        $value = $this->getDataByKey($key);
        assert(is_string($value), $this->getAssertDescription($key, $value));

        return $value;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getIntegerByKey(string $key): int
    {
        $value = $this->getDataByKey($key);
        assert(is_int($value), $this->getAssertDescription($key, $value));

        return $value;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getDataByKey(string $key): null|bool|float|int|string
    {
        $data = $this->bodyContentsToArray();

        $value = $data[$key] ?? null;
        assert($value === null || is_bool($value) || is_float($value) || is_int($value) || is_string($value));

        return $value;
    }

    private function getAssertDescription(null|bool|float|int|string $key, null|bool|float|int|string $value): string
    {
        return sprintf('Invalid response data for key %s. Value is %s with type %s', $key, $value, gettype($value));
    }

}
