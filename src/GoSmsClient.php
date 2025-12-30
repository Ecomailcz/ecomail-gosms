<?php

declare(strict_types = 1);

namespace EcomailGoSms;

use EcomailGoSms\Requests\MessageStatusRequest;
use EcomailGoSms\Requests\SendMessageAsyncRequest;
use EcomailGoSms\Requests\SendMessagesAsyncRequest;
use EcomailGoSms\Responses\MessageStatusResponse;
use EcomailGoSms\Responses\SendMessageAsyncResponse;
use EcomailGoSms\Responses\SendMessagesAsyncResponse;

final class GoSmsClient extends Client
{

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function sendMessageAsync(Message $message): SendMessageAsyncResponse
    {
        $request = new SendMessageAsyncRequest($message);

        return new SendMessageAsyncResponse($this->makeRequest($request));
    }

    /**
     * @param array<\EcomailGoSms\Message> $messages
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function sendMessagesAsync(array $messages): SendMessagesAsyncResponse
    {
        $request = new SendMessagesAsyncRequest($messages);

        return new SendMessagesAsyncResponse($this->makeRequest($request));
    }

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function getMessageStatistics(string $customId): MessageStatusResponse
    {
        $request = new MessageStatusRequest($customId);

        return new MessageStatusResponse($this->makeRequest($request));
    }

}
