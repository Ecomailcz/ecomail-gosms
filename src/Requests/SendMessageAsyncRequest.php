<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

use EcomailGoSms\Message;

final readonly class SendMessageAsyncRequest implements Request
{

    public function __construct(private Message $message)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $data = [
            'channel' => $this->message->channelId,
            'custom_id' => $this->message->customId,
            'message' => $this->message->message,
            'recipient' => $this->message->recipient,
        ];

        if ($this->message->expectedSendStart !== null) {
            $data['expected_send_start'] = $this->message->expectedSendStart;
        }

        return [
            'json' => $data,
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . self::API_PATH . '/messages/';
    }

}
