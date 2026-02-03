<?php

declare(strict_types = 1);

namespace EcomailGoSms\Responses;

use EcomailGoSms\Exceptions\InvalidResponseData;
use JsonException;
use Psr\Http\Message\ResponseInterface;

use function assert;
use function gettype;
use function is_array;
use function is_int;
use function is_string;
use function json_decode;
use function json_encode;
use function sprintf;

abstract class GoSmsResponse
{

    private readonly string $responseBody;

    public function __construct(private readonly ResponseInterface $response)
    {
        $this->responseBody = $response->getBody()->getContents();
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
            /** @var array<string, mixed>|null $decoded */
            $decoded = json_decode($this->responseBody, true, 512, JSON_THROW_ON_ERROR);

            if (!is_array($decoded)) {
                return [];
            }

            return $decoded;
        } catch (JsonException) {
            throw new InvalidResponseData($this->response);
        }
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getStringByKey(string $key): string
    {
        $value = $this->getDataByKey($key);

        if (!is_string($value)) {
            throw new InvalidResponseData($this->response, message: $this->getAssertDescription($key, $value));
        }

        return $value;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getIntegerByKey(string $key): int
    {
        $value = $this->getDataByKey($key);

        if (!is_int($value)) {
            throw new InvalidResponseData($this->response, message: $this->getAssertDescription($key, $value));
        }

        return $value;
    }

    /**
     * @return array<mixed, mixed>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getArrayByKey(string $key): array
    {
        $value = $this->getDataByKey($key);

        if (!is_array($value)) {
            throw new InvalidResponseData($this->response, message: $this->getAssertDescription($key, $value));
        }

        return $value;
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    protected function getDataByKey(string $key): mixed
    {
        $data = $this->bodyContentsToArray();

        $value = $data[$key] ?? null;
        assert($value === null || is_bool($value) || is_float($value) || is_int($value) || is_string($value) || is_array($value));

        return $value;
    }

    /**
     * @throws \JsonException
     */
    private function getAssertDescription(string $key, mixed $value): string
    {
        $valueForReport = is_array($value) ? json_encode($value, JSON_THROW_ON_ERROR) : $value;
        $formattedValue = is_scalar($valueForReport) ? (string) $valueForReport : json_encode($valueForReport, JSON_THROW_ON_ERROR);

        return sprintf(
            'Invalid response data for key %s. Value is %s with type %s',
            $key,
            $formattedValue,
            gettype($value),
        );
    }

}
