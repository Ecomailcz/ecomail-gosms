<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

final readonly class SendMessageAsyncRequest implements Request
{

    public function __construct(
        private string $message,
        private int $channelId,
        private string $recipient,
        private string $customId,
        private ?string $expectedSendStart = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'form_params' => [
                'channel' => $this->channelId,
                'custom_id' => $this->customId,
                'expected_send_start' => $this->expectedSendStart,
                'message' => $this->message,
                'recipient' => $this->recipient,
            ],
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . '/api/v2/messages/';
    }

}
