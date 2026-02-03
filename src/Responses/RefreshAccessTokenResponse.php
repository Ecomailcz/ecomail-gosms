<?php

declare(strict_types = 1);

namespace EcomailGoSms\Responses;

final class RefreshAccessTokenResponse extends GoSmsResponse
{

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getAccessToken(): string
    {
        return $this->getStringByKey('access_token');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getRefreshToken(): string
    {
        return $this->getStringByKey('refresh_token');
    }

    /**
     * @throws \EcomailGoSms\Exceptions\InvalidResponseData
     */
    public function getTokenType(): string
    {
        return $this->getStringByKey('token_type');
    }

}
