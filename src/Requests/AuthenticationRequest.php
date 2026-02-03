<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

final readonly class AuthenticationRequest implements Request
{

    public function __construct(private string $publicKey, private string $privateKey, private string $grantType = 'password', private string $scope = '')
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            'form_params' => [
                'client_id' => $this->publicKey,
                'client_secret' => $this->privateKey,
                'grant_type' => $this->grantType,
                'password' => $this->privateKey,
                'scope' => $this->scope,
                'username' => $this->publicKey,
            ],
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getEndpoint(): string
    {
        return self::BASE_URL . self::API_PATH . '/auth/token';
    }

}
