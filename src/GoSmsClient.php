<?php

declare(strict_types = 1);

namespace EcomailGoSms;

use EcomailGoSms\Requests\SendMessageAsyncRequest;
use EcomailGoSms\Responses\SendMessageAsyncResponse;

final class GoSmsClient extends Client
{

    /**
     * @throws \EcomailGoSms\Exceptions\BadRequest
     * @throws \Throwable
     */
    public function sendMessageAsync(
        string $message,
        int $channelId,
        string $recipient,
        string $customId,
        ?string $expectedSendStart = null,
    ): SendMessageAsyncResponse {
        $request = new SendMessageAsyncRequest($message, $channelId, $recipient, $customId, $expectedSendStart);

        return new SendMessageAsyncResponse($this->makeRequest($request));
    }

}
