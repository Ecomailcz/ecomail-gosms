<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

use function sprintf;

final readonly class MessageStatusRequest implements Request
{

    public function __construct(private string $customId) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $data = [
            'custom_id' => $this->customId,
        ];

        return [
            'json' => $data,
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . sprintf('/api/v2/messages/by-custom-id/%s', $this->customId);
    }

}
