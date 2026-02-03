<?php

declare(strict_types = 1);

namespace EcomailGoSms\Responses;

final class SendMessageAsyncResponse extends GoSmsResponse
{

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getStatus(): string
    {
        return $this->getStringByKey('status');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getRecipient(): string
    {
        return $this->getStringByKey('recipient');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getCustomId(): string
    {
        return $this->getStringByKey('custom_id');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getLink(): string
    {
        return $this->getStringByKey('link');
    }

}
