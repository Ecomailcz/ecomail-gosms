<?php

declare(strict_types = 1);

namespace EcomailGoSms;

final readonly class Message
{

    public function __construct(
        public string $message,
        public int $channelId,
        public string $recipient,
        public string $customId,
        public ?string $expectedSendStart = null,
    ) {
    }

    /**
     * @return array<string, int|string|null>
     */
    public function toArray(): array
    {
        return [
            'channel' => $this->channelId,
            'custom_id' => $this->customId,
            'expected_send_start' => $this->expectedSendStart,
            'message' => $this->message,
            'recipient' => $this->recipient,
        ];
    }

}
