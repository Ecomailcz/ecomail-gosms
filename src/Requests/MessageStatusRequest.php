<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

use function sprintf;

final readonly class MessageStatusRequest implements Request
{

    public function __construct(private string $customId)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . self::API_PATH . sprintf('/messages/by-custom-id/%s', $this->customId);
    }

}
