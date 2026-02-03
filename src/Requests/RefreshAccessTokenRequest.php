<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

final readonly class RefreshAccessTokenRequest implements Request
{

    public function __construct(private string $refreshToken)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'form_params' => [
                'refresh_token' => $this->refreshToken,
            ],
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . self::API_PATH . '/auth/refresh';
    }

}
