<?php

declare(strict_types = 1);

namespace EcomailGoSms\Requests;

interface Request
{

    public const string BASE_URL = 'https://api.gosms.eu';

    public const string API_PATH = '/api/v2';

    public function getMethod(): string;

    public function getEndpoint(): string;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

}
