<?php

declare(strict_types = 1);

namespace EcomailGoSms\Responses;

use EcomailGoSms\SentMessage;

use function collect;

final class SendMessagesAsyncResponse extends GoSmsResponse
{

    /**
     * @return array<\EcomailGoSms\SentMessage>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getAccepted(): array
    {
        /** @phpstan-ignore-next-line */
        return collect($this->getArrayByKey('accepted'))
            /** @phpstan-ignore-next-line */
            ->map(static fn (array $message): SentMessage => SentMessage::fromArray($message))
            ->toArray();
    }

    /**
     * @return array<\EcomailGoSms\SentMessage>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getRejected(): array
    {
        /** @phpstan-ignore-next-line */
        return collect($this->getArrayByKey('rejected'))
            /** @phpstan-ignore-next-line */
            ->map(static fn (array $message): SentMessage => SentMessage::fromArray($message))
            ->toArray();
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getTotalAccepted(): int
    {
        return $this->getIntegerByKey('total_accepted');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getTotalRejected(): int
    {
        return $this->getIntegerByKey('total_rejected');
    }

}
