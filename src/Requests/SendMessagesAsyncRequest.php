<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

use EcomailGoSms\Message;

use function collect;

final readonly class SendMessagesAsyncRequest implements Request
{

    /**
     * @param array<\EcomailGoSms\Message> $messages
     */
    public function __construct(private array $messages)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $messages = collect($this->messages)
            ->map(static fn (Message $message): array => $message->toArray());

        return [
            'json' => ['messages' => $messages->toArray()],
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . self::API_PATH . '/messages/bulk';
    }

}
