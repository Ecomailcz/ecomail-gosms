<?php

declare(strict_types = 1);

namespace EcomailGoSms\Responses;

final class MessageStatusResponse extends GoSmsResponse
{

    public function getCustomId(): string
    {
        return $this->getStringByKey('custom_id');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getTotalCount(): int
    {
        return $this->getIntegerByKey('total_count');
    }

    /**
     * @return array<array<string, mixed>>
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getMessages(): array
    {
        /** @var array<array<string, mixed>> $messages */
        $messages = $this->getArrayByKey('messages');

        return $messages;
    }

}
